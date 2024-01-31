<script type="text/javascript">
	document.addEventListener('alpine:init', () => {
		Alpine.data('referral', () => ({
			linkCopied: false,
			canNaviveShare: navigator.share ? true : false,
			isMobile: /iPhone|iPad|iPod|Android|webOS|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
			closeModal() {
				this.linkCopied = false;
				Alpine.store('modal').referral = false;
			},
			share() {
				if (navigator.share) {
					navigator.share({
						title: 'Orbit',
						text: 'link ',
						url: this.shareLink()
					})
					.then(() => console.log('Successfully shared'))
					.catch((error) => console.log('Error sharing:', error));
				}
			},

			shareLink() {
				return window.location.protocol + "//" + window.location.hostname + '/{{ app()->getLocale() }}/?referral=' + Alpine.store('user').info?.referral;
			} ,
			copyLink() {
				let textarea = document.createElement("textarea");
				textarea.value = this.shareLink();

				document.body.appendChild(textarea);

				textarea.select();

				try {
					document.execCommand('copy');
					this.linkCopied = true;
				} catch (err) {
					alert('Error copying link');
					this.linkCopied = false;
				} finally {
					document.body.removeChild(textarea);
				}
			}
		}))
	});
</script>

<div class="section-modal modal-receipt" id="social-media" x-data="referral" x-cloak x-show="$store.modal.referral">
	<div class="wrapper-modal">
		<div class="receipt-container">
			<div class="head custom-padding">
				<img src="{{ asset('assets/media/icons/stars_white.svg') }}" alt="" class="decoration">
				<h4 class="color-white">
					{!! trans('front.string_70') !!}
				</h4>
				<img src="{{ asset('assets/media/icons/close-icon_02.svg') }}" alt="" class="close-icon" @click="closeModal()">
			</div>
			<div class="body">
				<div class="row">
					<img src="{{ asset('assets/media/form_02.svg') }}" alt="">
					<p class="bold">{!! trans('front.string_71') !!}</p>
				</div>
				<div class="social-media">
					<a :href="'whatsapp://send?text='+shareLink()" x-cloak x-show="isMobile">
						<img src="{{ asset('assets/media/social/wh.svg') }}">
						<p>WhatsApp</p>
					</a>
					<a :href="'tg://msg?text='+shareLink()" x-cloak x-show="isMobile">
						<img src="{{ asset('assets/media/social/tg.svg') }}">
						<p>Telegram</p>
					</a>
					<a :href="'sms:?body=text='+shareLink()" x-cloak x-show="isMobile">
						<img src="{{ asset('assets/media/social/mm.svg') }}">
						<p>{!! trans('front.string_72') !!}</p>
					</a>
					<a href="#" x-cloak x-show="isMobile" @click.prevent="copyLink()">
						<img src="{{ asset('assets/media/social/link.svg') }}">
						<p>{!! trans('front.string_73') !!}</p>
					</a>
					<a href="#" x-cloak x-show="canNaviveShare" @click.prevent="share()">
						<img src="{{ asset('assets/media/social/add.svg') }}">
						<p>{!! trans('front.string_74') !!}</p>
					</a>
				</div>
				<div x-cloak x-show="!isMobile" class="share-input">
					<input type="text" :value="shareLink()" readonly />
					<div @click.prevent="copyLink()">
						<img src="{{ asset('assets/media/social/link.svg') }}"> 
						{!! trans('front.string_73') !!}
					</div>
				</div>
				<p class="bold">{!! trans('front.string_77') !!}</p>
			</div>
			<div class="footer">
				<p x-cloak x-show="!linkCopied">
					<a href="{{ asset('assets/files/terms_'.region().'.pdf') }}" class="term" target="_blank">
						{!! trans('front.string_76') !!}
					</a>
				</p>
				<h2 x-cloak x-show="linkCopied" style="text-align: center;">{!! trans('front.string_75') !!}</h2>
			</div>
		</div>
	</div>
</div>