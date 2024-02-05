<script type="text/javascript">
	document.addEventListener('alpine:init', () => {
		Alpine.data('feedback', () => ({
			success: null,
			email: null,
			text: null,
            messages: {},
            loading: false,
			async submit() {
                this.loading = true;
                try {
                    const sendData = {
                        'email': this.email,
                        'text': this.text
                    }

                    const response = await fetch('/api/{{ app()->getLocale() }}/mail', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${ Alpine.store('user').token }`,
                        },
                        body: JSON.stringify(sendData)
                    });

                    const result = await response.json();

                    if (!result.success) {
                        if (result.errors) {
                            this.messages = result.errors;
                        } else {
                            this.messages.message = [result.message];
                        }

                        throw result.errors || {
                            message: [result.message]
                        };
                    }

                    this.success = true;
                } catch (error) {
                    console.error('Error:', error);
                } finally {
                    this.loading = false;
                }
            },
            closeModal() {
                Alpine.store('modal').feedback = false;
                
                this.messages = {};
                this.email = null;
                this.text = null;
				this.success = null;
            },
		}))
	});
</script>

<div class="section-date-modal modal-form" id="FAQ-modal" x-cloak x-data="feedback" x-show="$store.modal.feedback">
	<div class="wrapper-modal">
		<div class="container-form">
			<div class="head">
				<img src="{{ asset('assets/media/icons/stars_blue.svg') }}" alt="" class="decoration">
				<h3>{!! trans('front.faq.ask') !!}</h3>
				<img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
			</div>
			<div class="body noFooter">
				<form class="form" x-show="!success">
					<div class="input-row">
						<input x-model="email" type="email" class="input" placeholder="E-Mail">
                        <span x-cloak x-show="messages?.email?.[0]" x-text="messages?.email?.[0]"></span>
					</div>
					<div class="input-row">
						<textarea x-model="text" class="textarea" placeholder="{!! trans('front.faq.write_your_question') !!}"></textarea>
                        <span x-cloak x-show="messages?.text?.[0]" x-text="messages?.text?.[0]"></span>
                        <span x-cloak x-show="messages?.message?.[0]" x-text="messages?.message?.[0]"></span>
					</div>
					<button type="button" class="button" @click="submit()">{!! trans('front.faq.send') !!}</button>
				</form>
				<div class="form" x-show="success" style="font-size: 20px; padding-top: 35px;">
                {!! trans('front.string_80') !!}
				</div>
			</div>
		</div>
	</div>
</div>