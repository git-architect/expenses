<template>
	<NcContent app-name="expenses">
		<NcAppNavigation>
			<NcAppNavigationNew text="New Expense">
				<template #icon>
					+
				</template>
			</NcAppNavigationNew>
			<NcAppNavigationList>
				<NcAppNavigationItem name="Invoices">
					<template>
						<NcAppNavigationItem name="Inbox" />
						<NcAppNavigationItem name="Unpaid" />
						<NcAppNavigationItem name="Paid" />
					</template>
				</NcAppNavigationItem>
				<NcAppNavigationItem name="Transactions">
					<template>
						<NcAppNavigationItem name="Transactions" />
						<NcAppNavigationItem name="Recurring" />
					</template>
				</NcAppNavigationItem>
				<NcAppNavigationItem name="Contacts" is-heading />
			</NcAppNavigationList>
			<template #footer>
				<div class="navigation__footer">
					<NcButton wide @click="settingsOpen = true">
						<template #icon>
							<IconCog />
						</template>
						Expenses settings
					</NcButton>
					<NcAppSettingsDialog :open.sync="settingsOpen"
						:show-navigation="false"
						name="Expense Settings"
						@close="toggleSettings">
						<SettingsModal />
					</NcAppSettingsDialog>
				</div>
			</template>
		</NcAppNavigation>
		<NcAppContent>
			<InvoiceList></InvoiceList>
		</NcAppContent>
	</NcContent>
</template>

<script>
import NcAppContent from '@nextcloud/vue/dist/Components/NcAppContent.js'
import NcAppSettingsDialog from '@nextcloud/vue/dist/Components/NcAppSettingsDialog.js'
import NcAppNavigation from '@nextcloud/vue/dist/Components/NcAppNavigation.js'
import NcAppNavigationItem from '@nextcloud/vue/dist/Components/NcAppNavigationItem.js'
import NcContent from '@nextcloud/vue/dist/Components/NcContent.js'
import NcAppNavigationList from '@nextcloud/vue/dist/Components/NcAppNavigationList.js'
import NcAppNavigationNew from '@nextcloud/vue/dist/Components/NcAppNavigationNew.js'
import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import IconCog from 'vue-material-design-icons/Cog.vue'
import SettingsModal from './components/SettingsModal.vue'
import InvoiceList from './components/InvoiceList.vue'

export default {
	name: 'App',
	components: {
		InvoiceList,
		NcContent,
		NcAppContent,
		NcAppNavigation,
		NcAppNavigationItem,
		NcAppNavigationList,
		NcAppNavigationNew,
		SettingsModal,
		NcButton,
		IconCog,
		NcAppSettingsDialog,
	},
	data() {
		return {
			settingsOpen: false,
		}
	},
	methods: {
	},
}
</script>

<style scoped lang="scss">
	.navigation__footer {
		display: flex;
		align-items: center;
		gap: 4px;
		padding: 4px;
	}
</style>
