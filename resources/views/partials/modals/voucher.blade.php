<script type="text/javascript">
	document.addEventListener('alpine:init', () => {
		Alpine.data('voucher', () => ({
			loading: false,
			page: 1,
			async buyVouchers() {
				this.loading = true;
				try {
					const sendData = {
						'id': Alpine.store('modal').voucherData.id
					}
					const response = await fetch('/api/{{ app()->getLocale() }}/vouchers', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'Authorization': `Bearer ${Alpine.store('user').token}`,
							'Accept': 'application/json',
						},
						body: JSON.stringify(sendData)
					});

					const result = await response.json();

					if (!result.success) {
						this.page = 3;
					} else {
						this.page = 2;
						Alpine.store('user').updateProfile();
					}

				} catch (error) {
					console.error('Error:', error);
				} finally {
					this.loading = false;
				}
			},
			closeModal() {
				Alpine.store('modal').voucher = false;
				Alpine.store('modal').voucherData = {};
				this.page = 1;
			}
		}))
	});
</script>

<div class="section-modal modal-form" x-data="voucher" x-cloak x-show="$store.modal.voucher">
	<div class="wrapper-modal">
		<template x-if="page == 1">
			<div class="container-form">
				<div class="head">
					<img src="{{ asset('assets/media/icons/stars_blue.svg') }}" alt="" class="decoration">
					<h4 class="padding-top">
					</h4>
					<img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
				</div>
				<div class="body">
					<img src="{{ asset('assets/media/icons/start_blue.svg') }}" alt="">
					<form class="form">
						<button type="button" class="button" @click="buyVouchers()" :disabled="loading">{!! trans('front.voucher.next') !!}</button>
					</form>
				</div>
				<div class="footer">
					<a class="align-left">{!! trans('front.voucher.title') !!}</a>
				</div>
			</div>
		</template>
		<template x-if="page == 2">
			<div class="container-form">
				<div class="head">
					<img src="{{ asset('assets/media/icons/stars_blue.svg') }}" alt="" class="decoration">
					<h4 class="padding-top">{!! trans('front.voucher.success') !!}</h4>
					<img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
				</div>
				<div class="body">
					<img src="{{ asset('assets/media/icons/start_blue.svg') }}" alt="">
					<form class="form">
						<a href="/{{ app()->getLocale() }}#game" class="button" @click="closeModal()">{!! trans('front.voucher.play_more') !!}</a>
					</form>
				</div>
				<div class="footer">
					<a class="align-left">{!! trans('front.voucher.footer1') !!}</a>
				</div>
			</div>
		</template>
		<template x-if="page == 3">
			<div class="container-form">
				<div class="head">
					<img src="{{ asset('assets/media/icons/stars_blue.svg') }}" alt="" class="decoration">
					<h4 class="padding-top">
						<span>{!! trans('front.voucher.fail') !!}</span>
					</h4>
					<img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
				</div>
				<div class="body">
					<img src="{{ asset('assets/media/icons/smile.svg') }}" alt="">
					<form class="form">
						<a href="/{{ app()->getLocale() }}#game" class="button" @click="closeModal()">{!! trans('front.voucher.collect_coin') !!}</a>
					</form>
				</div>
				<div class="footer">
					<a class="align-left">{!! trans('front.voucher.footer2') !!}</a>
				</div>
			</div>

		</template>
	</div>
</div>