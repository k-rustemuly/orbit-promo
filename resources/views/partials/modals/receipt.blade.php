<script type="text/javascript">
	document.addEventListener('alpine:init', () => {
		Alpine.data('receipt', () => ({
			page: 0,
			file: null,
			fileChanged(event) {
				const fileInput = event.target;
				if (fileInput.files.length > 0) {
					this.file = fileInput.files[0];
					this.uploadFile(this.page == 0 || this.page == 1 ? 0 : 1);
				} else {
					this.file = null;
				}
			},
			async uploadFile(isManual = 0) {
				this.file = null;

				this.page = isManual == 0 ? 3 : 5;
			},
			closeModal() {
				this.page = 1;
				Alpine.store('modal').receipt = false;
				Alpine.store('user').updateProfile();
			},
		}))
	});
</script>

<div class="section-modal modal-receipt" id="receipt-add" x-data="receipt" x-cloak x-show="$store.modal.receipt">
	<template x-if="$store.modal.receipt">
		<div class="wrapper-modal" x-data="{page: $store.modal.receiptPage}">
			<template x-if="page == 0 || page == 1 || page == 4">
				<div class="receipt-container">
					<div class="head">
						<img src="{{ asset('assets/media/icons/stars_white.svg') }}" alt="" class="decoration">
						<template x-if="page == 0">
							<h4 class="color-white">
								Упс, <br>
								тебе не хватает жизней,  <br>
								чтобы продолжить игру :(
							</h4>
						</template>
						<template x-if="page == 1">
							<h4 class="color-white">
								Пополни жизни, <br>
								чтобы продолжить <br>
								игру!
							</h4>
						</template>
						<template x-if="page == 4">
							<h4 class="color-white">
							Загрузи фото <br> всего чека
							</h4>
						</template>
						<img src="{{ asset('assets/media/icons/close-icon_02.svg') }}" alt="" class="close-icon" @click="closeModal()">
					</div>
					<div class="body">
						<div class="table">
							<div>
								<template x-if="page == 0 || page == 1">
									<h5>СКАНИРУЙ<br> QR ЧЕКА</h5>
								</template>
								<template x-if="page == 4">
									<h5>ЗАГРУЗИ <br> ВЕСЬ ЧЕК</h5>
								</template>
								<img src="{{ asset('assets/media/arrows.svg') }}" alt="" class="arrows">
								<img src="{{ asset('assets/media/form_02.svg') }}" alt="" class="image">
								<p>1 чек = <span>5 жизней</span> <br>в игре</p>
							</div>
							<div>
								<template x-if="page == 0 || page == 1">
									<img src="{{ asset('assets/media/receipt_qr.png') }}" alt="" class="receipt" />
								</template>
								<template x-if="page == 4">
									<img src="{{ asset('assets/media/receipt_all.png') }}" alt="" class="receipt" />
								</template>
							</div>
						</div>
						<a href="#" class="button button_custom upload-btn" @click.prevent="$refs.fileInput.click()">ЗАГРУЗИТЬ<img src="{{ asset('assets/media/icons/camera.svg') }}"></a>
						<input type="file" class="hidden" x-ref="fileInput" accept=".jpg, .jpeg, .png" @change="fileChanged" />

						<template x-if="page == 0 || page == 1">
							<p class="color-white bold">или пригласить друга</p>
						</template>
					</div>
					<div class="footer">
						<p>Нажимая кнопку “Загрузить”, <br>
							я подтверждаю, что согласен с Правилами Акции <br>
							и Политикой Конфидициальности
						</p>
					</div>
				</div>

			</template>
			<template x-if="page == 3">
				<div class="receipt-container">
					<div class="head">
						<img src="{{ asset('assets/media/icons/stars_white.svg') }}" alt="" class="decoration">
						<h4 class="color-blue">
							Странно, <br>с твоим чеком <br>что-то не так ...
						</h4>
						<img src="{{ asset('assets/media/icons/close-icon_02.svg') }}" alt="" class="close-icon" @click="closeModal()">
					</div>
					<div class="body">
						<p>
						пожалуйста отсканируйте <br> весь чек
						</p>
						<img src="{{ asset('assets/media/warning.svg') }}" alt="" class="warning">
						<p>Убедись, что твой чек <br>
							соответствует примеру <br>
							и попробуй загрузить его ещё раз
						</p>
						<a href="#" class="custom-button" @click.prevent="page = 4">ПРОДОЛЖИТЬ</a>
					</div>
					<div class="footer">
						<p>Нажимая кнопку “Продолжить”, <br>
							я подтверждаю, что согласен с Правилами Акции <br>
							и Политикой Конфидециальности</p>
					</div>
				</div>
			</template>
			<template x-if="page == 5 || page == 6">
				<div class="receipt-container">
					<div class="head">
						<img src="{{ asset('assets/media/icons/stars_white.svg') }}" alt="" class="decoration">
						<h4 class="color-white">
							Твой Чек принят!
						</h4>
						<img src="{{ asset('assets/media/icons/close-icon_02.svg') }}" alt="" class="close-icon">
					</div>
					<div class="body">
						<p>
							Скорей отправляйся <br>
							в <span>ИГРУ</span> для сбора <br>
							предметов
						</p>
						<img src="{{ asset('assets/media/form_01.svg') }}" alt="" class="warning">
						<a href="#" class="custom-button" @click.prevent="closeModal()">ПРОДОЛЖИТЬ ИГРУ</a>
					</div>
					<div class="footer">
						<p>Нажимая кнопку “Назад”, <br>
							я подтверждаю, что согласен с Правилами Акции <br>
							и Политикой Конфидециальности</p>
					</div>
				</div>
			</template>
		</div>
	</template>
</div>