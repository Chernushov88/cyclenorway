(function (wp) {
	if (typeof wp === "undefined" || !wp.data) {
		return
	}

	let wasSaving = false

	function findAcfBlocksRecursively (blocks, foundBlocks = []) {
		blocks.forEach(function (block) {
			if (block.name.startsWith("profidev/")) {
				foundBlocks.push(block)
			}
			if (block.innerBlocks && block.innerBlocks.length > 0) {
				findAcfBlocksRecursively(block.innerBlocks, foundBlocks)
			}
		})

		return foundBlocks
	}

	wp.data.subscribe(function () {
		const editor = wp.data.select("core/editor")
		if (!editor) {
			return
		}

		const isSaving = editor.isSavingPost()
		const isAutosaving = editor.isAutosavingPost()
		const isSaveSuccessful = editor.didPostSaveRequestSucceed()

		if (isSaving && !isAutosaving) {
			wasSaving = true
		}

		if (wasSaving && !isSaving && isSaveSuccessful) {
			wasSaving = false

			const topLevelBlocks = wp.data.select("core/block-editor").getBlocks()
			const allAcfBlocks = findAcfBlocksRecursively(topLevelBlocks)

			if (allAcfBlocks.length === 0) {
				return
			}

			// 1. Update the ACF blocks to force a visual refresh
			allAcfBlocks.forEach((block) => {
				wp.data.dispatch("core/block-editor").updateBlockAttributes(block.clientId, {
					data: {
						...(block.attributes.data || {}),
						_profidev_refresh: Date.now(),
					},
				})
			})

			// 2. Erase the "unsaved changes" warning
			setTimeout(() => {
				const postType = editor.getCurrentPostType()
				const postId = editor.getCurrentPostId()

				const currentEditedRecord = wp.data.select("core").getEditedEntityRecord("postType", postType, postId)
				wp.data.dispatch("core").receiveEntityRecords("postType", postType, [currentEditedRecord])

			}, 200)
		}
	})
})(window.wp)
