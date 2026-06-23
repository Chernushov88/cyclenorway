import { BaseElement } from "~js/scripts.js"
import Swiper from "swiper"
import { A11y, Autoplay, Pagination } from "swiper/modules"

class LogosSliderElement extends BaseElement {
	connectedCallback () {
		super.connectedCallback()
		const slider = this.querySelector(".logos-slider .swiper")
		if (!slider) {return}

		const paginationEl = this.querySelector(".slider-pagination")

		const params = {
			slidesPerView: 'auto',
			loop: true,
			autoplay: {
				delay: 2500,
				disableOnInteraction: false
			},
			modules: [ A11y, Autoplay, Pagination],
			pagination: paginationEl ? { el: paginationEl, clickable: true } : undefined,
		}

		this.swiper = new Swiper(slider, params)
	}

	disconnectedCallback () {
		this.swiper?.destroy(true, true)
		this.swiper = null
	}
}

if (!customElements.get("profidev-logos-slider")) {
	customElements.define("profidev-logos-slider", LogosSliderElement)
}