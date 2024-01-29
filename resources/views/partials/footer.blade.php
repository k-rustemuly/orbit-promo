<script type="text/javascript">
	document.addEventListener('alpine:init', () => {
		Alpine.data('footer', () => ({
			modal1() {
				Alpine.store('modal').modal = {
					show: true,
					title: `{!! trans('front.string_60') !!}`,
					content: `{!! trans('front.string_65') !!}`
				};
			},
			modal2() {
				Alpine.store('modal').modal = {
					show: true,
					title: `{!! trans('front.string_61') !!}`,
					content: `{!! trans('front.string_66') !!}`
				};
			},
			modal3() {
				Alpine.store('modal').modal = {
					show: true,
					title: `{!! trans('front.string_62') !!}`,
					content: `{!! trans('front.string_67') !!}`
				};
			},
			modal4() {
				Alpine.store('modal').modal = {
					show: true,
					title: `{!! trans('front.string_63') !!}`,
					content: `{!! trans('front.string_68') !!}`
				};
			},
			modal5() {
				Alpine.store('modal').modal = {
					show: true,
					title: `{!! trans('front.string_64') !!}`,
					content: `{!! trans('front.string_69') !!}`
				};
			},
		}))
	});
</script>

<footer class="wrapper-full section-footer">
	<div class="wrapper-fix wrapper-middle wrapper-footer">
		<div class="container-footer">
			<div class="container-footer__column-01">
				<a href="/">
					<img src="{{ asset('assets/media/logotype.svg') }}" alt="Logo">
				</a>
			</div>
			<div class="container-footer__column-02">
				<h4>{!! trans('front.string_58') !!}</h4>
				<p x-data="footer">
					{!! trans('front.string_59') !!}<br/>
					
					<a href="#" @click.prevent="modal1()">{!! trans('front.string_60') !!}</a> | 
					<a href="#" @click.prevent="modal2()">{!! trans('front.string_61') !!}</a> | 
					<a href="#" @click.prevent="modal3()">{!! trans('front.string_62') !!}</a> | 
					<a href="#" @click.prevent="modal4()">{!! trans('front.string_63') !!}</a> | 
					<a href="#" @click.prevent="modal5()">{!! trans('front.string_64') !!}</a>
				</p>
			</div>
		</div>
	</div>
</footer>

