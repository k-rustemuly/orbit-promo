<script type="text/javascript">
	document.addEventListener('alpine:init', () => {
		Alpine.data('modal', () => ({
			closeModal() {
				Alpine.store('modal').modal = {};
			}
		}))
	});
</script>

<div class="section-modal modal-form" x-data="modal" x-cloak x-show="$store.modal.modal?.show" style="max-width: 700px;">
	<div class="wrapper-modal">
		<div class="container-form">
			<div class="head">
				<img src="{{ asset('assets/media/icons/stars_blue.svg') }}" alt="" class="decoration">
				<h4 class="padding-top" x-text="$store.modal.modal?.title"></h4>
				<img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
			</div>
			<div class="body" x-html="$store.modal.modal?.content"></div>
		</div>
	</div>
</div>