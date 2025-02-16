<template>
	<NcAppSettingsSection id="storage-config" name="Storage Configuration">
		<NcTextField v-model="pathInbox"
			label="Invoice Inbox Folder"
			placeholder="/path/to/folder1"
			@click="chooseNcFolder('Inbox')" />
		<NcTextField v-model="pathFiling"
			label="Invoice Filing Folder"
			placeholder="/path/to/folder2"
			@click="chooseNcFolder('Filing')" />

		<NcButton variant="primary" @click="saveSettings">
			Save
		</NcButton>
		<NcButton variant="secondary" @click="$emit('close')">
			Cancel
		</NcButton>

		<PathFilePicker />
	</NcAppSettingsSection>
</template>

<script>
import NcAppSettingsSection from '@nextcloud/vue/dist/Components/NcAppSettingsSection.js'
import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import NcTextField from '@nextcloud/vue/dist/Components/NcTextField.js'
import { getFilePickerBuilder } from '@nextcloud/dialogs'
import PathFilePicker from './PathFilePicker.vue'

export default {
	name: 'SettingsModal',
	components: {
		NcAppSettingsSection,
		NcButton,
		NcTextField,
		PathFilePicker,
	},
	data() {
		return {
			pathInbox: '/',
			pathFiling: '/',
		}
	},
	methods: {
		async chooseNcFolder(title, initial = '/') {
		    const picker = getFilePickerBuilder(title).setMultiSelect(false).addMimeTypeFilter('https/unix-directory').allowDirectories().startAt(initial).build()
			const folder = await picker.pick()
			alert(folder)
		},
	},
}
</script>

<style scoped lang="scss">
// nothing yet
</style>
