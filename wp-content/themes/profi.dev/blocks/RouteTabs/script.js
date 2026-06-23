import { BaseElement } from "~js/scripts.js"

class RouteTabs extends BaseElement {
	onAjaxLoadRoutes = undefined
	// elLoadMore = undefined
	// elRouteList = undefined

	connectedCallback () {
		super.connectedCallback()
		this.elLoadMore = Array.from(this.querySelectorAll("[data-type=\"load-more\"]"))
		this.elRouteList = Array.from(this.querySelectorAll(".theme-grid.route-list"))
		this.onAjaxLoadRoutes = this.on("[data-type=\"load-more\"]", "click", this.ajaxLoadRoutes.bind(this))
	}

	ajaxLoadRoutes (event) {
		event.preventDefault()
		this.elLoadMore.forEach(el => el.classList.add("loading"))
		this.elRouteList.forEach(el => el.classList.add("loading"))
		this.adminAjaxClient.request(event.target.getAttribute("data-href"), { method: "GET" })
			.then((response) => {
				const parser = new DOMParser().parseFromString(response.content, "text/html")
				const button = parser.querySelector("[data-type=\"load-more\"]")
				this.elLoadMore.forEach((el) => {
					if (button) {
						el.setAttribute("data-href", button.getAttribute("data-href"))
					} else {
						el.style.display = "none"
					}
				})
				const routeList = parser.querySelector(".theme-grid.route-list")
				this.elRouteList.forEach((el) => {
					el.append(...Array.from(routeList.childNodes))
				})
				console.warn(response)
			}).catch((reason) => {
				console.warn(reason)
			}).finally(() => {
				this.elLoadMore.forEach(el => el.classList.remove("loading"))
				this.elRouteList.forEach(el => el.classList.remove("loading"))
			})
	}

	disconnectedCallback () {
		this.onAjaxLoadRoutes?.()
	}
}

if (!customElements.get("route-tabs")) {
	customElements.define("route-tabs", RouteTabs)
}

class RouteMapTabs extends BaseElement {
	onTabClick = undefined

	connectedCallback () {
		super.connectedCallback()

		this.elButtons = Array.from(this.querySelectorAll(".route-buttons [role=\"tab\"]"))
		this.elMaps = Array.from(this.querySelectorAll(".theme-maps-tabs > *"))

		if (!this.elButtons.length || !this.elMaps.length) {
			return
		}

		this.initMaps()

		this.onTabClick = this.on(
			".route-buttons [role=\"tab\"]",
			"click",
			this.handleTabClick.bind(this)
		)
	}

	initMaps () {
		this.showMap(0)
	}

	handleTabClick (event) {
		const button = event.target
		let index = this.elButtons.indexOf(button)

		if (index === -1) {
			return
		}

		if (!this.elMaps[index]) {
			index = 0
		}

		this.showMap(index)
	}

	showMap (activeIndex) {
		this.elMaps.forEach((el, index) => {
			const isActive = index === activeIndex

			el.classList.toggle("active", isActive)

			if (isActive) {
				el.removeAttribute("hidden")
				el.setAttribute("aria-hidden", "false")
			} else {
				el.setAttribute("hidden", "")
				el.setAttribute("aria-hidden", "true")
			}
		})
	}

	disconnectedCallback () {
		this.onTabClick?.()
	}
}

if (!customElements.get("route-map-tabs")) {
	customElements.define("route-map-tabs", RouteMapTabs)
}