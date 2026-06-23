/*
 * This file SHOULD NOT contain any imports or heavy code. This injected to the very top of the header to load as soon as possible.
 */
class AdminAjaxClient {
	static #instance

	/**
	 * Make http request
	 *
	 * @param {Response} response
	 * @param {Function} resolve
	 * @param {Function} reject
	 * @private
	 */
	async _parse_response (response, resolve, reject) {
		const contentType = response.headers.get("Content-Type")
		let content
		if (contentType.includes("application/json")) {
			content = await response.json()
		}
		else {
			content = await response.text()
		}

		if (response.ok) {
			resolve(content)
		} else {
			reject(content)
		}
	}

	/**
	 * Helper method to handle the actual fetch call and response parsing.
	 *
	 * @param {string|URL} url - The URL to request.
	 * @param {RequestInit} options - Configuration options for the fetch request.
	 * @returns {Promise<object>} - The parsed JSON response data.
	 */
	async request (url, options) {
		return new Promise((resolve, reject) => {
			fetch(url, options).then((response) => {
				this._parse_response(response, resolve, reject)
			})
		})
	}

	/**
	 * Generate client url
	 *
	 * @param {object} query
	 * @returns {string}
	 */
	getActionUrl (query = {}) {
		const url = new URL(window["ProfiDevThemeVars"].url)
		const searchParams = new URLSearchParams(query)
		searchParams.append("nonce", window["ProfiDevThemeVars"].nonce)
		url.search = searchParams.toString()
		return url.toString()
	}

	/**
	 * Sends a GET request.
	 *
	 * @param {object} query
	 * @param {RequestInit} options
	 * @returns {Promise<Object>}
	 */
	async get (query = {}, options = {}) {
		return this.request(this.getActionUrl(query), Object.assign({ method: "GET" }, options))
	}

	/**
	 * Sends a POST request with a JSON body.
	 *
	 * @param {object} data - The data to send in the request body
	 * @param {RequestInit} options - Configuration options for the fetch request.
	 * @returns {Promise<object>}
	 */
	async post (data = {}, options = {}) {
		data.nonce = window["ProfiDevThemeVars"].nonce
		return this.request(
			this.getActionUrl(),
			Object.assign(
				{ method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(data) },
				options
			)
		)
	}

	/**
	 * Get single client instance
	 *
	 * @returns {AdminAjaxClient}
	 */
	static getClient () {
		if (!AdminAjaxClient.#instance) {
			AdminAjaxClient.#instance = new AdminAjaxClient()
		}

		return AdminAjaxClient.#instance
	}
}

class SupportUtility {
	/**
	 * Get inner html from html
	 *
	 * @param {string} html
	 * @param {string} selector
	 * @returns {string | undefined}
	 */
	static getSectionInnerHTML (html, selector = ".section-name") {
		return new DOMParser().parseFromString(html, "text/html").querySelector(selector)?.innerHTML
	}

	/**
	 * Get inner text from html
	 *
	 * @param {string} html
	 * @param {string} selector
	 * @returns {string|undefined}
	 */
	static getSectionInnerText (html, selector = ".section-name") {
		return new DOMParser().parseFromString(html, "text/html").querySelector(selector)?.innerText
	}

	/**
	 * Replace selectors with specific values from html
	 *
	 * @param {string} html
	 * @param {string[]} selectors
	 * @param {Document|HTMLElement} context
	 */
	static replaceSelectors (html, selectors = [], context = document) {
		if (!Array.isArray(selectors) || selectors.length === 0) {
			return
		}
		const parser = new DOMParser().parseFromString(html, "text/html")
		selectors.forEach((selector) => {
			const element = context.querySelector(selector)
			if (element) {
				const newElement = parser.querySelector(selector)
				if (!newElement || newElement.outerHTML.trim().length === 0) {
					element.style.display = "none"
				} else {
					element.outerHTML = newElement.outerHTML
					element.style.display = null
				}
			}
		})
	}
}

class BaseElement extends HTMLElement {
	adminAjaxClient = AdminAjaxClient.getClient()

	constructor () {
		super()
	}

	connectedCallback () {
		if (this.hasAttribute("classname")) {
			this.setAttribute("class", this.getAttribute("classname"))
			this.removeAttribute("classname")
		}
	}

	/**
	 * Debounce function
	 *
	 * @param func
	 * @param {number} delay
	 * @returns {(function(...[*]): void)|*}
	 */
	debounce (func, delay = 300) {
		let timeout

		return function (...args) {
			const context = this
			clearTimeout(timeout)

			timeout = setTimeout(() => {
				func.apply(context, args)
			}, delay)
		}
	}

	/**
	 * Submit form with replace selectors and callbacks
	 *
	 * @param {String|HTMLElement|NodeList} element - The trigger element(s).
	 * @param {string[]} selectors - Array of CSS selectors to be replaced in the DOM.
	 * @param {Object} [callbacks={}] - Optional lifecycle hooks.
	 * @param {Function} [callbacks.pre_request] - Runs before the AJAX request is sent.
	 * @param {Function} [callbacks.success_request] - Runs after a successful response. Receives `responseText` as an argument.
	 * @param {Function} [callbacks.reject_request] - Runs if the request fails. Receives the `reason` (error) as an argument.
	 * @param {Function} [callbacks.post_request] - Runs after the request settles (both success or failure).
	 */
	onPaginate (element, selectors = [], callbacks = {}) {
		this.on(element, "click", (event) => {
			event.preventDefault()
			const link = event.target.closest("[href]")
			if (!link) {
				throw new Error("Element with [href] not found")
			}
			if (link.getAttribute("href").length === 0) {
				throw new Error("Element attribute href empty")
			}

			if ("pre_request" in callbacks) {
				callbacks.pre_request()
			}
			this.adminAjaxClient.request(link.getAttribute("href"), { method: "GET" })
				.then((response) => response.text()).then((responseText) => {
					SupportUtility.replaceSelectors(responseText, selectors)
					window.history.pushState(
						{ selectors },
						SupportUtility.getSectionInnerText(responseText, "title"),
						link.getAttribute("href")
					)
					if ("success_request" in callbacks) {
						callbacks.success_request(responseText)
					}
				}).catch((reason) => {
					if ("reject_request" in callbacks) {
						callbacks.reject_request(reason)
					}
				}).finally(() => {
					if ("post_request" in callbacks) {
						callbacks.post_request()
					}
				})
		})
	}

	/**
	 * Submit form with replace selectors and callbacks
	 *
	 * @param {String|HTMLElement|NodeList} element - The trigger element(s).
	 * @param {string[]} selectors - Array of CSS selectors to be replaced in the DOM.
	 * @param {Object} [callbacks={}] - Optional lifecycle hooks.
	 * @param {Function} [callbacks.pre_request] - Runs before the AJAX request is sent.
	 * @param {Function} [callbacks.success_request] - Runs after a successful response. Receives `responseText` as an argument.
	 * @param {Function} [callbacks.reject_request] - Runs if the request fails. Receives the `reason` (error) as an argument.
	 * @param {Function} [callbacks.post_request] - Runs after the request settles (both success or failure).
	 */
	onSubmit (element, selectors = [], callbacks = {}) {
		this.on(element, "submit", (event) => {
			event.preventDefault()
			const form = event.target.closest("form")
			if (!form) {
				throw new Error("Form not found for this action")
			}
			if (!form.hasAttribute("action")) {
				throw new Error("Action required for form")
			}

			if ("pre_request" in callbacks) {
				callbacks.pre_request()
			}
			const method = (form.getAttribute("method") || "post").toUpperCase()
			const body = new FormData(form)
			this.adminAjaxClient.request(
				form.getAttribute("action"),
				{
					method: method,
					body: body
				}
			).then((response) => response.text()).then((responseText) => {
				SupportUtility.replaceSelectors(responseText, selectors)
				if (method === "GET") {
					const url = new URL(form.getAttribute("action"))
					url.search = (new URLSearchParams(body)).toString()
					window.history.pushState({ selectors }, SupportUtility.getSectionInnerText(responseText, "title"), url)
				}
				if ("success_request" in callbacks) {
					callbacks.success_request(responseText)
				}
			}).catch((reason) => {
				if ("reject_request" in callbacks) {
					callbacks.reject_request(reason)
				}
			}).finally(() => {
				if ("post_request" in callbacks) {
					callbacks.post_request()
				}
			})
		})
	}

	/**
	 * Subscribe event by selector
	 *
	 * @template {keyof HTMLElementEventMap} T
	 * @param {String|HTMLElement|NodeList} element
	 * @param {T} event
	 * @param {(event: HTMLElementEventMap[T]) => void} handler
	 * @param {EventListenerOptions | boolean} options
	 * @returns {(function(): void)}
	 */
	on (element, event, handler, options= false) {
		if (element instanceof HTMLElement) {
			return this.onElement(element, event, handler, options)
		} else if (typeof element === "string") {
			return this.onSelector(element, event, handler, options)
		} else if (element instanceof NodeList) {
			return this.onElements(element, event, handler, options)
		}

		throw new Error(`Element ${element} is not a known element`)
	}

	/**
	 * Subscribe event by selector
	 *
	 * @template {keyof HTMLElementEventMap} T
	 * @param {String} selector
	 * @param {T} event
	 * @param {(event: HTMLElementEventMap[T]) => void} handler
	 * @param {EventListenerOptions | boolean} options
	 * @returns {(function(): void)}
	 */
	onSelector (selector, event, handler, options) {
		const elements = this.querySelectorAll(selector)
		elements.forEach((el) => {
			el.addEventListener(event, handler, options)
		})

		return () => {
			elements.forEach((el) => {
				el.removeEventListener(event, handler, options)
			})
		}
	}

	/**
	 * Subscribe event by element
	 *
	 * @template {keyof HTMLElementEventMap} T
	 * @param {HTMLElement} element
	 * @param {T} event
	 * @param {(event: HTMLElementEventMap[T]) => void} handler
	 * @param {EventListenerOptions | boolean} options
	 * @returns {(function(): void)}
	 */
	onElement (element, event, handler, options) {
		element.addEventListener(event, handler, options)

		return () => {
			element.removeEventListener(event, handler, options)
		}
	}

	/**
	 * Subscribe event by element
	 *
	 * @template {keyof HTMLElementEventMap} T
	 * @param {NodeList} elements
	 * @param {T} event
	 * @param {(event: HTMLElementEventMap[T]) => void} handler
	 * @param {EventListenerOptions | boolean} options
	 * @returns {(function(): void)}
	 */
	onElements (elements, event, handler, options) {
		elements.forEach((el) => {
			el.addEventListener(event, handler, options)
		})

		return () => {
			elements.forEach((el) => {
				el.removeEventListener(event, handler, options)
			})
		}
	}

	/**
	 * Get computed value
	 *
	 * @param {string} property
	 * @param {string} measure
	 * @param {HTMLElement} element
	 * @returns {number}
	 */
	getComputedValue (property, measure = "px", element = document.documentElement) {
		const computedStyle = getComputedStyle(element)
		let value = computedStyle.getPropertyValue(property)

		if (measure === "px" && value.includes("rem")) {
			const rootFontSize = parseFloat(
				getComputedStyle(document.documentElement).fontSize
			)
			const remValue = parseFloat(value)
			value = remValue * rootFontSize
		}

		return parseInt(value)
	}

	/**
	 * On Element resize
	 *
	 * @param {Element} element
	 * @param {() => void} handler
	 * @param {"height" | "width" | "x" | "y" | "bottom" | "left" | "right" | "top" | null} property
	 * @returns {function(number|DOMRect): void}
	 */
	onElementResize (element, handler, property = null) {
		if (!element) {
			throw new Error(`Element ${element} is not a valid element`)
		}

		const resizeObserver = new ResizeObserver(() => {
			const rect = element.getBoundingClientRect()
			if (property) {
				handler(rect[property])
			}
			else {
				handler(rect)
			}
		})

		resizeObserver.observe(element)

		return () => resizeObserver.disconnect()
	}

	/**
	 * Set element variable
	 *
	 * @param {HTMLElement} element
	 * @param {string} variable
	 * @param {string} value
	 */
	setVariable (element, variable = "--element-variable", value = "undefined") {
		if (!element) {
			throw new Error(`Element ${element} is not a valid element`)
		}
		element.style.setProperty(variable, value)
	}

	/**
	 * Set pixel variable
	 *
	 * @param {HTMLElement} element
	 * @param {string} variable
	 * @param {number} value
	 */
	setVariablePx (element, variable = "--element-variable", value = 14) {
		this.setVariable(element, variable, value.toString().concat("px"))
	}

	/**
	 * Get single element inside current custom element
	 *
	 * @param {string} selector
	 * @param {ParentNode} root
	 * @returns {Element|null}
	 */
	$ (selector, root = this) {
		return root.querySelector(selector)
	}

	/**
	 * Get multiple elements inside current custom element
	 *
	 * @param {string} selector
	 * @param {ParentNode} root
	 * @returns {Element[]}
	 */
	$$ (selector, root = this) {
		return Array.from(root.querySelectorAll(selector))
	}

	/**
	 * Tabs helper for custom elements
	 *
	 * @param {Object} options
	 * @param {string} options.buttonSelector
	 * @param {string} options.panelSelector
	 * @param {string} options.activeClass
	 * @param {string} options.hiddenClass
	 * @param {number} options.defaultTab
	 * @returns {{activate: Function, buttons: Element[], panels: Element[]}|undefined}
	 */
	useTabs (options = {}) {
		const {
			buttonSelector = "[data-tab-button]",
			panelSelector = "[data-tab-panel]",
			activeClass = "active",
			hiddenClass = "hidden",
			defaultTab = 0
		} = options

		const buttons = this.$$(buttonSelector)
		const panels = this.$$(panelSelector)

		if (!buttons.length || !panels.length) {
			return
		}

		const activateTab = (index) => {
			const currentButton = buttons[index]
			if (!currentButton) {
				return
			}

			const tabId = currentButton.dataset.tabButton

			buttons.forEach((button, buttonIndex) => {
				const isActive = buttonIndex === index
				button.classList.toggle(activeClass, isActive)
				button.setAttribute("aria-selected", String(isActive))
				button.setAttribute("tabindex", isActive ? "0" : "-1")
			})

			panels.forEach((panel) => {
				const isActive = panel.dataset.tabPanel === tabId
				panel.classList.toggle(hiddenClass, !isActive)
				panel.hidden = !isActive
			})
		}

		buttons.forEach((button, index) => {
			this.on(button, "click", () => {
				activateTab(index)
			})
		})

		activateTab(defaultTab)

		return {
			activate: activateTab,
			buttons,
			panels
		}
	}
}

window.addEventListener("popstate", function (event) {
	if (!("selectors" in event.state)) {
		return
	}
	const client = AdminAjaxClient.getClient()
	client.request(document.location.toString(), {}).then((response) => response.text()).then((responseText) => {
		SupportUtility.replaceSelectors(responseText, event.state.selectors)
	})
})

class Header extends BaseElement {
    onToggleMenu = undefined
    onSubmenuClick = undefined
    onToggleSearch = undefined

    _boundHandleOutsideClick = undefined
    _boundHandleEsc = undefined

    connectedCallback() {
        this.onToggleMenu = this.on(
            this.querySelectorAll("button.header-mobile-menu"),
            "click",
            this.toggleMenu.bind(this)
        )

        this.onSubmenuClick = this.on(
            this.querySelectorAll(".header-menu a"),
            "click",
            this.onMenuLinkClick.bind(this)
        )

        this.onToggleSearch = this.on(
            this.querySelectorAll(".header-btn-search"),
            "click",
            this.toggleSearch.bind(this)
        )

		this.onSubMenuToggle = this.on(
            this.querySelectorAll(".sub-menu-toggle"),
            "click",
            this.toggleSubMenu.bind(this)
        )

        this._boundHandleOutsideClick = this.handleClickOutside.bind(this)
        this._boundHandleEsc = (e) => {
            if (e.key === "Escape") {
                this.closeMenu()
                this.getSearchForm()?.classList.remove("active")
            }
        }

        document.addEventListener("click", this._boundHandleOutsideClick)
        document.addEventListener("keydown", this._boundHandleEsc)
    }

    getMenu() {
        return this.querySelector(".header-menu")
    }

    getToggleBtn() {
        return this.querySelector("button.header-mobile-menu")
    }

    getSearchForm() {
        return this.querySelector(".header-search .search-form")
    }

    handleClickOutside(event) {
        const menu = this.getMenu()
        const searchForm = this.getSearchForm()
        const isOpen = menu?.classList.contains("open")
        const isSearchActive = searchForm?.classList.contains("active")

        if (!isOpen && !isSearchActive) return

        const headerInner = this.querySelector('.theme-header')

        if (headerInner && !headerInner.contains(event.target)) {
            if (isOpen) this.closeMenu()
            if (isSearchActive) searchForm.classList.remove("active")
        }
    }

    toggleSearch(event) {
        event.preventDefault()
        const searchForm = this.getSearchForm()
        if (!searchForm) return

        const isActive = searchForm.classList.toggle("active")
        
        if (isActive) {
            this.closeMenu()
            setTimeout(() => searchForm.querySelector('input')?.focus(), 100)
        }
    }

    openMenu() {
        const menu = this.getMenu()
        const btn = this.getToggleBtn()

        if (!menu || menu.classList.contains("open")) return

        menu.classList.add("open")
        btn?.classList.add("open")
        btn?.setAttribute("aria-expanded", "true")
        document.body.classList.add("freeze")
    }

    closeMenu() {
        const menu = this.getMenu()
        const btn = this.getToggleBtn()

        if (!menu || !menu.classList.contains("open")) return

        menu.classList.remove("open")
        btn?.classList.remove("open")
        btn?.setAttribute("aria-expanded", "false")
        document.body.classList.remove("freeze")
    }

    toggleMenu(event) {
        const menu = this.getMenu()
        if (!menu) return

        if (menu.classList.contains("open")) {
            this.closeMenu()
        } else {
            this.getSearchForm()?.classList.remove("active")
            this.openMenu()
        }
    }

	toggleSubMenu(event) {
        event.preventDefault()
        const btn = event.currentTarget
        const parentLi = btn.closest(".menu-item-has-children")
        
        if (!parentLi) return

        const isOpen = parentLi.classList.toggle("submenu-open")
        
        btn.setAttribute("aria-expanded", isOpen ? "true" : "false")
    }

    onMenuLinkClick(event) {
        this.closeMenu()
    }

    disconnectedCallback() {
        this.onToggleMenu?.()
        this.onSubmenuClick?.()
        this.onToggleSearch?.()
		this.onSubMenuToggle?.() 

        document.removeEventListener("click", this._boundHandleOutsideClick)
        document.removeEventListener("keydown", this._boundHandleEsc)
    }
}

if (!customElements.get("header-element")) {
    customElements.define("header-element", Header)
}

class ThemeTabsElement extends BaseElement {
  connectedCallback() {
    this.instances = [];
    this.cleanups = [];

    this.initAllTabs();
  }

  initAllTabs() {
    const tabsBlocks = this.querySelectorAll(".theme-tabs");

    if (!tabsBlocks.length) {
      return;
    }

    tabsBlocks.forEach((tabsBlock) => {
      this.initTabsBlock(tabsBlock);
    });
  }

  initTabsBlock(tabsBlock) {
    const firstButton = tabsBlock.querySelector('[role="tab"][data-parent-id]');
    if (!firstButton) {
      return;
    }

    const parentId = firstButton.getAttribute("data-parent-id");
    if (!parentId) {
      return;
    }

    const tabButtons = Array.from(
      tabsBlock.querySelectorAll(`[role="tab"][data-parent-id="${parentId}"]`)
    );

    const tabs = Array.from(
      tabsBlock.querySelectorAll(`.tab-block[data-parent-id="${parentId}"]`)
    );

    if (!tabButtons.length || !tabs.length) {
      return;
    }

    const activateTab = (currentButton) => {
      const tabID = currentButton.id;

      tabButtons.forEach((button) => {
        const isActive = button === currentButton;
        button.setAttribute("aria-selected", String(isActive));
        button.classList.toggle("active", isActive);
        button.setAttribute("tabindex", isActive ? "0" : "-1");
      });

      tabs.forEach((tab) => {
        const isActive = tab.getAttribute("aria-labelledby") === tabID;
        tab.classList.toggle("hidden", !isActive);
        tab.hidden = !isActive;
      });
    };

    const handleTabClick = (e) => {
      e.preventDefault();
      activateTab(e.currentTarget);
    };

    tabButtons.forEach((button) => {
      button.addEventListener("click", handleTabClick);

      this.cleanups.push(() => {
        button.removeEventListener("click", handleTabClick);
      });
    });

    const activeButton =
      tabButtons.find((button) => button.getAttribute("aria-selected") === "true") ||
      tabButtons[0];

    if (activeButton) {
      activateTab(activeButton);
    }

    this.instances.push({
      tabsBlock,
      parentId,
      tabButtons,
      tabs,
    });
  }

  disconnectedCallback() {
    this.cleanups?.forEach((cleanup) => cleanup());
    this.cleanups = [];
    this.instances = [];
  }
}

if (!customElements.get("profidev-theme-tabs")) {
  customElements.define("profidev-theme-tabs", ThemeTabsElement);
}

class ProfiDevAccordions extends BaseElement {
	connectedCallback () {
		super.connectedCallback()
		this.items = Array.from(this.querySelectorAll(".accordion")).filter(item =>
			item.closest("profidev-accordions") === this
		)

		this.breakpoint = parseInt(this.getAttribute("data-breakpoint")) || null
		this.onlyOne = this.hasAttribute("data-only-one")
		this.isDefaultOpen = this.hasAttribute("data-default-open")

		this.init()

		if (this.breakpoint) {
			window.addEventListener("resize", () => this.handleResize())
		}
	}

	isAccordionMode () {
		return !this.breakpoint || window.innerWidth < this.breakpoint
	}

	init () {
		this.items.forEach((item, index) => {
			const btn = item.querySelector(".accordion-head")
			const content = item.querySelector(".accordion-content")
			if (!btn || !content) {return}

			this.updateItemState(item, index === 0)

			btn.addEventListener("click", (e) => {
				if (!this.isAccordionMode()) {return}
				e.stopPropagation()
				this.toggleItem(item)
			})
		})

		this.handleResize()
	}

	handleResize () {
		const accordionMode = this.isAccordionMode()

		if (this.breakpoint) {
			this.classList.toggle("is-disabled", !accordionMode)
		}

		this.items.forEach(item => this.updateItemState(item))
	}

	updateItemState (item, isFirst = false) {
		const content = item.querySelector(".accordion-content")
		const btn = item.querySelector(".accordion-head")
		if (!content || !btn) {return}

		if (!this.isAccordionMode()) {
			content.style.height = "auto"
			item.classList.remove("active")
			btn.setAttribute("aria-expanded", "true")
		} else {
			const shouldBeOpen = item.classList.contains("active") || (this.isDefaultOpen && isFirst)
			if (shouldBeOpen) {
				this.open(item, false)
			} else {
				this.close(item, false)
			}
		}
	}

	toggleItem (item) {
		const isActive = item.classList.contains("active")
		if (!isActive && this.onlyOne) {
			this.items.forEach(other => { if (other !== item) {this.close(other)} })
		}
		isActive ? this.close(item) : this.open(item)
	}

	open (item, animate = true) {
		const content = item.querySelector(".accordion-content")
		const btn = item.querySelector(".accordion-head")

		item.classList.add("active")
		btn.setAttribute("aria-expanded", "true")

		if (!animate) {
			content.style.height = "auto"
			return
		}

		content.style.height = "0px"
		content.style.height = content.scrollHeight + "px"

		const onEnd = () => {
			if (item.classList.contains("active")) {content.style.height = "auto"}
			content.removeEventListener("transitionend", onEnd)
		}
		content.addEventListener("transitionend", onEnd)
	}

	close (item, animate = true) {
		const content = item.querySelector(".accordion-content")
		const btn = item.querySelector(".accordion-head")

		item.classList.remove("active")
		btn.setAttribute("aria-expanded", "false")

		if (!animate) {
			content.style.height = "0px"
			return
		}

		content.style.height = content.scrollHeight + "px"
		requestAnimationFrame(() => {
			content.style.height = "0px"
		})
	}
}

if (!customElements.get("profidev-accordions")) {
	customElements.define("profidev-accordions", ProfiDevAccordions)
}

export {
	AdminAjaxClient,
	SupportUtility,
	BaseElement,
}
