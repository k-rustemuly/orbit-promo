<script type="text/javascript">
	document.addEventListener('alpine:init', () => {
		Alpine.data('receipt', () => ({
			page: 1,
			file: '',
			closeModal() {
				Alpine.store('modal').receipt = false;
			},
		}))
	});
</script>

<div class="section-modal modal-receipt" id="receipt-add" x-data="receipt" x-cloak x-show="$store.modal.receipt">
	<div class="wrapper-modal">
		<div class="receipt-container">
			<div class="head">
				<img src="{{ asset('assets/media/icons/stars_white.svg') }}" alt="" class="decoration">
				<h4 class="color-white">
					Пополни жизни, <br>
					чтобы продолжить <br>
					игру!
				</h4>
				<img src="{{ asset('assets/media/icons/close-icon_02.svg') }}" alt="" class="close-icon" @click="closeModal()">
			</div>
			<div class="body">
				<div class="table">
					<div>
						<h5>ЗАГРУЗИ <br> ЧЕК</h5>
						<img src="{{ asset('assets/media/arrows.svg') }}" alt="" class="arrows">
						<img src="{{ asset('assets/media/form_02.svg') }}" alt="" class="image">
						<p>1 чек = <span>5 жизней</span> <br>в игре</p>
					</div>
					<div>
						<img src="{{ asset('assets/media/receipt.png') }}" alt="" class="receipt">
					</div>
				</div>
				<a href="#" class="button button_custom upload-btn">ЗАГРУЗИТЬ<img src="{{ asset('assets/media/icons/camera.svg') }}"></a>
				<input type="file" x-model="file" class="hidden" />

				<p class="color-white bold">или пригласить друга</p>
			</div>
			<div class="footer">
				<p>Нажимая кнопку “Загрузить”, <br>
					я подтверждаю, что согласен с Правилами Акции <br>
					и Политикой Конфидициальности
				</p>
			</div>
		</div>
	</div>
</div>