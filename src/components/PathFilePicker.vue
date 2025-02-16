<template>
	<FilePicker name="Pick some files" />
</template>
<script>
import {
	FilePickerVue as FilePicker,
} from '@nextcloud/dialogs'

export default {
	name: 'PathFilePicker',
	components: {
		FilePicker,
	},
}
</script>
