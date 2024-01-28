<section class="wrapper-full section-present" id="prizes">
	<div class="wrapper-fix wrapper-small wrapper-present">
		<div class="container-present" x-data="{
			data: [],
			async init() {
                try {
                    this.loading = true;
                    
                    const response = await fetch('/api/{{ app()->getLocale() }}/prizes', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        }
                    });

                    const result = await response.json();
                    
                    if(result.success) {
						this.data = result.data;
                    }

                } catch (error) {
                    console.error('Error:', error);
                } finally {
                    this.loading = false;
                }
            },
			showVoucherModal(data) {
				if($store.user.token) {
					$store.modal.voucherData = data;
					$store.modal.voucher = true;
				} else {
					$store.modal.signIn = true;
				}
			}
		}">
			<h3 class="title">ВИТРИНА призов</h3>

			<div class="present-block">
				<div class="present-block_item">
					<div class="caption">
						<div>
							<img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
							<span x-text="data?.[0]?.coin"></span>
						</div>
						<p x-text="data?.[0]?.name"></p>
					</div>
					<a href="#" @click.prevent="showVoucherModal(data?.[0])">УЧАСТВОВАТЬ</a>
					<div class="image image-01">
						<img src="{{ asset('assets/media/present_01.png') }}" alt="">
					</div>
					<div class="decoration">
						<img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
					</div>
				</div>

				<div class="present-block_item">
					<div class="caption">
						<div>
							<img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
							<span x-text="data?.[1]?.coin"></span>
						</div>
						<p x-text="data?.[1]?.name"></p>
					</div>
					<a href="#" @click.prevent="showVoucherModal(data?.[1])">УЧАСТВОВАТЬ</a>
					<div class="image image-02">
						<img src="{{ asset('assets/media/present_02.png') }}" alt="">
					</div>
					<div class="decoration">
						<img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
					</div>
				</div>

				<div class="present-block_item">
					<div class="caption">
						<div>
							<img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
							<span x-text="data?.[2]?.coin"></span>
						</div>
						<p x-text="data?.[2]?.name"></p>
					</div>
					<a href="#" @click.prevent="showVoucherModal(data?.[2])">УЧАСТВОВАТЬ</a>
					<div class="image image-03">
						<img src="{{ asset( region() == 'kz' ? 'assets/media/present_03.png' : 'assets/media/present_03_uz.png') }}" alt="">
					</div>
					<div class="decoration">
						<img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
					</div>
				</div>
			</div>

			<div class="present-block-mobile">
				<div class="present-slider owl-carousel owl-theme">
					<div class="present-block_item item">
						<div class="caption">
							<div>
								<img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
								<span>350</span>
							</div>
							<p>Колонка</p>
						</div>
						<a href="#" @click.prevent="showVoucherModal(data?.[0])">УЧАСТВОВАТЬ</a>
						<div class="image image-01">
							<img src="{{ asset('assets/media/present_01.png') }}" alt="">
						</div>
						<div class="decoration">
							<img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
						</div>
					</div>

					<div class="present-block_item item">
						<div class="caption">
							<div>
								<img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
								<span>500</span>
							</div>
							<p>Наушники</p>
						</div>
						<a href="#" @click.prevent="showVoucherModal(data?.[1])">УЧАСТВОВАТЬ</a>
						<div class="image image-02">
							<img src="{{ asset('assets/media/present_02.png') }}" alt="">
						</div>
						<div class="decoration">
							<img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
						</div>
					</div>

					<div class="present-block_item item">
						<div class="caption">
							<div>
								<img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
								<span>1000</span>
							</div>
							<p>Планшет</p>
						</div>
						<a href="#" @click.prevent="showVoucherModal(data?.[2])">УЧАСТВОВАТЬ</a>
						<div class="image image-03">
							<img src="{{ asset( region() == 'kz' ? 'assets/media/present_03.png' : 'assets/media/present_03_uz.png') }}" alt="">
						</div>
						<div class="decoration">
							<img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>