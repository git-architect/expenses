<template>
	<NcAppContent page-heading="Invoice Inbox">
		<NcLoadingIcon v-if="loading" />
		<NcAppContentList v-else>
			<NcListItem v-for="invoice in invoices" :key="invoice.id" :name="invoice.name">
				<template #subname>
					Received: {{ formatDate(invoice.mtime) }}
				</template>
			</NcListItem>
		</NcAppContentList>
	</NcAppContent>
</template>

<script>
import NcAppContent from '@nextcloud/vue/dist/Components/NcAppContent.js'
import NcAppContentList from '@nextcloud/vue/dist/Components/NcAppContentList.js'
import NcListItem from '@nextcloud/vue/dist/Components/NcListItem.js'
import NcLoadingIcon from '@nextcloud/vue/dist/Components/NcLoadingIcon.js'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

export default {
	name: 'InvoiceList',
	components: {
		NcAppContent,
		NcLoadingIcon,
		NcAppContentList,
		NcListItem,
	},
	data() {
		return {
			invoices: [],
			loading: false,
			error: null,
		}
	},
	mounted() {
		this.fetchInvoices()
	},
	methods: {
		async fetchInvoices() {
			this.loading = true
			this.error = null
			try {
				const response = await axios.get(generateUrl('apps/expenses/api/invoice/inbox'), {
					headers: {
						Accept: 'application/json',
					},
				})
				this.invoices = response.data
			} catch (err) {
				this.error = err.message
			} finally {
				this.loading = false
			}
		},
		formatDate(timestamp) {
			return new Date(timestamp * 1000).toLocaleString()
		},
	},
}
</script>

<style scoped lang="scss">
//No Content
</style>
