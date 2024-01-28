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
					<h4 class="padding-top">Списать <span x-text="$store.modal.voucherData.coin"></span> коинов
						для участия <br>
						в розыгрыше <br>
						<span x-text="$store.modal.voucherData.name"></span>?
					</h4>
					<img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
				</div>
				<div class="body">
					<img src="{{ asset('assets/media/icons/start_blue.svg') }}" alt="">
					<form class="form">
						<button type="button" class="button" @click="buyVouchers()" :disabled="loading">ПРОДОЛЖИТЬ</button>
					</form>
				</div>
				<div class="footer">
					<a class="align-left">Нажимая кнопку “Продолжить”, <br>
						я подтверждаю, что согласен с <br>
						Правилами Акции и Политикой Конфидициальности</a>
				</div>
			</div>
		</template>
		<template x-if="page == 2">
			<div class="container-form">
				<div class="head">
					<img src="{{ asset('assets/media/icons/stars_blue.svg') }}" alt="" class="decoration">
					<h4 class="padding-top">Отлично, коины списаны, теперь Ты — участник розыгрыша <span x-text="$store.modal.voucherData.name"></span>. <span>Удачи!<span></h4>
					<img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
				</div>
				<div class="body">
					<img src="{{ asset('assets/media/icons/start_blue.svg') }}" alt="">
					<form class="form">
						<a href="/{{ app()->getLocale() }}#game" class="button" @click="closeModal()">Играть ещё</a>
					</form>
				</div>
				<div class="footer">
					<a class="align-left">Нажимая кнопку "Играть",  <br>
						Я подтверждаю, что согласен с Правилами Акции <br>
						и Политикой Конфидициальности</a>
				</div>
			</div>
		</template>
		<template x-if="page == 3">
			<div class="container-form">
				<div class="head">
					<img src="{{ asset('assets/media/icons/stars_blue.svg') }}" alt="" class="decoration">
					<h4 class="padding-top">
						<span>УПС! <br>
							немного не хватает <br>
							для участия <br>
							в розыгрыше</span>
					</h4>
					<img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
				</div>
				<div class="body">
					<img src="{{ asset('assets/media/icons/smile.svg') }}" alt="">
					<form class="form">
						<a href="/{{ app()->getLocale() }}#game" class="button" @click="closeModal()">СОБРАТЬ КОИНЫ</a>
					</form>
				</div>
				<div class="footer">
					<a class="align-left">Нажимая кнопку "Играть",  <br>
						Я подтверждаю, что согласен с Правилами Акции <br>
						и Политикой Конфидициальности</a>
				</div>
			</div>

		</template>
	</div>
</div>