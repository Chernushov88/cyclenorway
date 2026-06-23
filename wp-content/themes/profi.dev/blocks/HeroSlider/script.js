import { BaseElement } from "~js/scripts.js"
import Swiper from "swiper"
import { Navigation, A11y, Pagination, EffectFade } from "swiper/modules"

class PostSliderElement extends BaseElement {
	swiper = null
	slidesObserver = null

	isEditor () {
		const classes = ["block-editor-iframe__body", "block-editor-page"];
    	return classes.some(cls => document.body.classList.contains(cls));
	}

	connectedCallback () {
		super.connectedCallback()
		this.initSlider()

		if (this.isEditor()) {
			this.observeEditorSlides()
		}
	}

	disconnectedCallback () {
		this.slidesObserver?.disconnect()
		this.slidesObserver = null

		this.swiper?.destroy(true, true)
		this.swiper = null
	}

	initSlider () {
		const slider = this.querySelector(".swiper")

		if (!slider) {return}

		const nextEl = this.querySelector(".theme-slider-controls .next")
		const prevEl = this.querySelector(".theme-slider-controls .prev")
		const paginationEl = this.querySelector(".slider-pagination")

		this.swiper?.destroy(true, true)
		this.swiper = null

		const params = {
			slidesPerView: 1,
			effect: "fade",
			loop: true,
			modules: [Navigation, A11y, Pagination, EffectFade],
			navigation: nextEl && prevEl ? { nextEl, prevEl } : undefined,
			pagination: paginationEl ? { el: paginationEl, clickable: true } : undefined,
			slideClass: this.isEditor() ? "wp-block-profidev-hero-slide" : "swiper-slide",
			observer: true,
			observeParents: true,
			observeSlideChildren: true,
			allowTouchMove: !this.isEditor(),
		}

		this.swiper = new Swiper(slider, params)
	}

	getEditorSlidesCount () {
		const wrapper = this.querySelector(".swiper-wrapper")
		if (!wrapper) {return 0}

		return wrapper.querySelectorAll(":scope > .wp-block-profidev-hero-slide").length
	}

	goToLastSlide () {
		if (!this.swiper) {return}

		this.swiper.update()

		const lastIndex = this.swiper.slides.length - 1
		if (lastIndex < 0) {return}

		this.swiper.slideTo(lastIndex, 0)
	}

	observeEditorSlides () {
		const wrapper = this.querySelector(".swiper-wrapper")
		if (!wrapper) {return}

		this.slidesObserver?.disconnect()

		let prevSlidesCount = this.getEditorSlidesCount()

		this.slidesObserver = new MutationObserver(() => {
			const currentSlidesCount = this.getEditorSlidesCount()

			if (currentSlidesCount === prevSlidesCount) {return}

			const wasAdded = currentSlidesCount > prevSlidesCount
			prevSlidesCount = currentSlidesCount

			if (!this.swiper) {
				this.initSlider()
				return
			}

			this.swiper.update()

			if (wasAdded) {
				requestAnimationFrame(() => {
					this.goToLastSlide()
				})
			}
		})

		this.slidesObserver.observe(wrapper, {
			childList: true,
			subtree: true,
		})
	}
}

if (!customElements.get("profidev-hero-slider")) {
	customElements.define("profidev-hero-slider", PostSliderElement)
}
