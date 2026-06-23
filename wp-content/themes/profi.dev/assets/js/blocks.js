import justifiedLayout from "justified-layout"
import PhotoSwipeLightbox from "photoswipe/lightbox"
import Swiper from "swiper"
import { Navigation, A11y, Pagination, Autoplay} from "swiper/modules"
import { BaseElement } from "./scripts.js"

const lightboxZoomConfig = {
	paddingFn: () => window.innerWidth <= 768
		? {
			top: 20,
			bottom: 20,
			left: 0,
			right: 0
		}
		: {
			top: 40,
			bottom: 40,
			left: 80,
			right: 80
		},
	initialZoomLevel: 'fit',
	secondaryZoomLevel: 'fit',
	maxZoomLevel: 1
}

const setupPhotoSwipeContainHack = (lightbox) => {
	lightbox.addFilter('domItemData', (itemData, element, linkEl) => {
		if (linkEl) {
			const rawW = parseInt(linkEl.dataset.pswpWidth, 10) || 0
			const rawH = parseInt(linkEl.dataset.pswpHeight, 10) || 0

			if (rawW > 0 && rawW < 1400) {
				const multiplier = 4
				itemData.w = rawW * multiplier
				itemData.h = rawH * multiplier
			} else {
				itemData.w = rawW
				itemData.h = rawH
			}
			itemData.src = linkEl.href
		}
		return itemData
	})
}

class ProfiDevGalleryJustified extends BaseElement {
	connectedCallback () {
		super.connectedCallback()

		this.gallery = this.$(".js-gallery")
		if (!this.gallery) { return }

		this.items = this.$$(".gallery-item")
		if (!this.items.length) { return }

		const isLoadMoreMode = this.gallery.dataset.type === "load-more"
		this.loadMoreBtn = this.$(".more-photos")

		this.isExpanded = !isLoadMoreMode
		this.cachedSizes = this.getSizes()

		this.initLayout()
		this.initLightbox()

		if (isLoadMoreMode && this.loadMoreBtn) {
			this.loadMoreBtn.addEventListener("click", () => {
				this.isExpanded = true
				this.initLayout()
				this.loadMoreBtn.style.display = "none"
			})
		} else if (this.loadMoreBtn) {
			this.loadMoreBtn.style.display = "none"
		}

		this.resizeObserver = new ResizeObserver(() => {
			if (!this.isConnected) { return }

			window.requestAnimationFrame(() => {
				this.initLayout()
			})
		})

		this.resizeObserver.observe(this.gallery)
	}

	getSizes () {
		return this.items.map((item) => {
			const link = item.querySelector("a")

			return {
				width: parseInt(link.dataset.pswpWidth, 10),
				height: parseInt(link.dataset.pswpHeight, 10),
			}
		})
	}

	initLayout () {
		const containerWidth = this.gallery.getBoundingClientRect().width
		if (containerWidth === 0) { return }

		const layout = justifiedLayout(this.cachedSizes, {
			containerWidth,
			targetRowHeight: 330,
			boxSpacing: 10
		})

		this.applyLayout(layout)
	}

	applyLayout (layout) {
		this.gallery.style.position = "relative"

		let finalContainerHeight = layout.containerHeight

		if (!this.isExpanded) {
			const rows = [...new Set(layout.boxes.map(box => box.top))].sort((a, b) => a - b)

			if (rows.length > 2) {
				finalContainerHeight = rows[2] - 10
			}
		}

		this.gallery.style.height = `${finalContainerHeight}px`
		this.gallery.style.overflow = this.isExpanded ? "visible" : "hidden"

		layout.boxes.forEach((box, i) => {
			const el = this.items[i]

			el.style.position = "absolute"
			el.style.width = `${box.width}px`
			el.style.height = `${box.height}px`
			el.style.transform = `translate3d(${box.left}px, ${box.top}px, 0)`

			if (!this.isExpanded && box.top >= finalContainerHeight) {
				el.style.opacity = "0"
				el.style.visibility = "hidden"
				el.style.pointerEvents = "none"
			} else {
				el.style.opacity = "1"
				el.style.visibility = "visible"
				el.style.pointerEvents = "auto"
			}
		})
	}

	initLightbox () {
		this.lightbox = new PhotoSwipeLightbox({
			gallery: this.gallery,
			children: "a",
			pswpModule: () => import("photoswipe"),
			...lightboxZoomConfig
		})

		setupPhotoSwipeContainHack(this.lightbox)
		this.lightbox.init()
	}

	disconnectedCallback () {
		this.resizeObserver?.disconnect()
		this.lightbox?.destroy()
	}
}

if (!customElements.get("profidev-gallery-justified")) {
	customElements.define("profidev-gallery-justified", ProfiDevGalleryJustified)
}

class GallerySliderElement extends BaseElement {
	connectedCallback () {
		super.connectedCallback()
		const sliderEl = this.querySelector(".swiper")
		if (!sliderEl) {return}

		const nextEl = this.querySelector(".theme-slider-controls .next")
		const prevEl = this.querySelector(".theme-slider-controls .prev")

		const params = {
			slidesPerView: 1,
			loop: true,
			spaceBetween: 16,
			modules: [Navigation, A11y, Pagination, Autoplay],
			navigation: nextEl && prevEl ? { nextEl, prevEl } : undefined,
			autoplay: {
				delay: 2500,
				disableOnInteraction: false
			},
			pagination: {
				el: this.querySelector(".slider-pagination"),
				clickable: true,
				dynamicBullets: true
			},
		}

		this.swiper = new Swiper(sliderEl, params)

		this.initLightbox(sliderEl)
	}

	initLightbox (sliderEl) {
		this.lightbox = new PhotoSwipeLightbox({
			gallery: sliderEl,
			children: ".swiper-slide:not(.swiper-slide-duplicate) a",
			pswpModule: () => import("photoswipe"),
			...lightboxZoomConfig
		})

		setupPhotoSwipeContainHack(this.lightbox)
		this.lightbox.init()
	}

	disconnectedCallback () {
		this.swiper?.destroy(true, true)
		this.swiper = null

		this.lightbox?.destroy()
		this.lightbox = null
	}
}

if (!customElements.get("profidev-gallery-slider")) {
	customElements.define("profidev-gallery-slider", GallerySliderElement)
}

class ProfiDevGalleryGrid extends BaseElement {
	connectedCallback () {
		super.connectedCallback()
		this.gallery = this.$(".js-gallery")
		if (!this.gallery) {return}

		this.initLightbox()
	}

	initLightbox () {
		this.lightbox = new PhotoSwipeLightbox({
			gallery: this.gallery,
			children: "a",
			pswpModule: () => import("photoswipe"),
			...lightboxZoomConfig
		})

		setupPhotoSwipeContainHack(this.lightbox)
		this.lightbox.init()
	}

	disconnectedCallback () {
		this.lightbox?.destroy()
	}
}

if (!customElements.get("profidev-gallery-grid")) {
	customElements.define("profidev-gallery-grid", ProfiDevGalleryGrid)
}
