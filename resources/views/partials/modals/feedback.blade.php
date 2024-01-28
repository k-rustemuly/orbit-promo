<div class="section-modal modal-form mfp-hide" id="FAQ-modal">
	<div class="wrapper-modal">
		<div class="container-form">
			<div class="head">
				<img src="{{ asset('assets/media/icons/stars_blue.svg') }}" alt="" class="decoration">
				<h3>{!! trans('front.faq.ask') !!}</h3>
				<img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon">
			</div>
			<div class="body noFooter">
				<form action="./" class="form">
					<div class="input-row">
						<input type="text" name="name" class="input" placeholder="E-Mail">
						<span></span>
					</div>
					<div class="input-row">
						<textarea class="textarea" placeholder="{!! trans('front.faq.write_your_question') !!}"></textarea>
					</div>
					<button type="submit" class="button">{!! trans('front.faq.send') !!}</button>
				</form>
			</div>
		</div>
	</div>
</div>