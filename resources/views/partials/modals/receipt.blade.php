<script type="text/javascript">
	document.addEventListener('alpine:init', () => {
		Alpine.data('receipt', () => ({
			page: 0,
			file: null,
			loading: false,
			fileChanged(event) {
				const fileInput = event.target;
				if (fileInput.files.length > 0) {
					this.file = fileInput.files[0];
					this.uploadFile();
				} else {
					this.file = null;
				}
			},
			async uploadFile(qrUrl) {
				const isManual = qrUrl ? 0 : 1;
				const formData = new FormData();
				formData.append('is_manual', isManual);
				
				if (isManual) {
					formData.append('file', this.file);
				} else {
					formData.append('url', qrUrl);
				}

				this.loading = true;
				try {
					const response = await fetch('/api/{{ app()->getLocale() }}/receipts/recognize', {
						method: 'POST',
						headers: {
							// 'Content-Type': 'application/json',
							'Authorization': `Bearer ${ Alpine.store('user').token }`,
							'Accept': 'application/json',
						},
						body: formData
					});
					const result = await response.json();
					if (result.success) {
						this.page = isManual ? 6 : 5;
					} else {
						this.file = null;
						this.page = 3;
					}
					Alpine.store('user').updateProfile();
				} catch (error) {
					console.error('Error:', error);
				} finally {
					this.loading = false;
				}
			},
			showReferralModal() {
				this.closeModal();
				Alpine.store('modal').referral = true;
			},
			async startScanning() {
				this.page = 7;
				await this.$nextTick();
				video = document.createElement("video");
				canvasElement = document.getElementById("canvas");
				canvas = canvasElement.getContext("2d");
				loadingMessage = document.getElementById("loadingMessage");
				qrUrl = null;

				navigator.mediaDevices.getUserMedia({
					video: {
						facingMode: "environment"
					}
				}).then((stream) => {
					videoStream = stream; // Store the video stream
					video.srcObject = stream;
					video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
					video.play();
					
					requestAnimationFrame(this.tick.bind(this));
				});
			},

			stopScanning() {
				qrCodeDetected = true; // Set the flag to true to stop further scanning

				// Close the camera stream
				if (videoStream) {
					const tracks = videoStream.getTracks();
					tracks.forEach(track => track.stop());
				}
			},

			tick() {
				loadingMessage.innerText = "⌛ Loading video..."
				if (video.readyState === video.HAVE_ENOUGH_DATA && !qrCodeDetected) {
					loadingMessage.hidden = true;
					canvasElement.hidden = false;

					canvasElement.height = video.videoHeight;
					canvasElement.width = video.videoWidth;
					canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
					var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
					var code = jsQR(imageData.data, imageData.width, imageData.height, {
						inversionAttempts: "dontInvert",
					});
					if (code && code.data) {
						drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
						drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
						drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
						drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");

						this.page = 1;
						this.uploadFile(code.data);

						this.stopScanning(); // Call the function to stop scanning and close the camera
					}
				}
				if (!qrCodeDetected) {
					requestAnimationFrame(this.tick.bind(this));
				}
			},
			closeModal() {
				this.page = 1;
				Alpine.store('modal').receipt = false;
				this.stopScanning();
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
								{!! trans('front.string_39') !!}
							</h4>
						</template>
						<template x-if="page == 1">
							<h4 class="color-white">
								{!! trans('front.string_40') !!}
							</h4>
						</template>
						<template x-if="page == 4">
							<h4 class="color-white">
								{!! trans('front.string_41') !!}
							</h4>
						</template>
						<img src="{{ asset('assets/media/icons/close-icon_02.svg') }}" alt="" class="close-icon" @click="closeModal()">
					</div>
					<div class="body">
						<div class="table">
							<div>
								<template x-if="page == 0 || page == 1">
									<h5>{!! trans('front.string_42') !!}</h5>
								</template>
								<template x-if="page == 4">
									<h5>{!! trans('front.string_43') !!}</h5>
								</template>
								<img src="{{ asset('assets/media/arrows.svg') }}" alt="" class="arrows">
								<img src="{{ asset('assets/media/form_02.svg') }}" alt="" class="image">
								<p>{!! trans('front.string_44') !!}</p>
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

						<template x-if="page == 0 || page == 1">
							<div>
								<a href="#" class="button button_custom upload-btn" @click.prevent="startScanning()">{!! trans('front.string_79') !!}<img src="{{ asset('assets/media/icons/camera.svg') }}"></a>
								<br><br>
								<a href="#" class="button button_custom upload-btn" @click.prevent="page = 4">{!! trans('front.string_78') !!}</a>
							</div>
						</template>

						<template x-if="page == 4">
							<a href="#" class="button button_custom upload-btn" @click.prevent="$refs.fileInput.click()">{!! trans('front.string_45') !!}<img src="{{ asset('assets/media/icons/camera.svg') }}"></a>
							<input type="file" class="hidden" x-ref="fileInput" accept=".jpg, .jpeg, .png" @change="fileChanged" :disabled="loading" />
						</template>
						<template x-if="loading == true"><span class="spinner"></span></template>

						<template x-if="page == 0 || page == 1">
							<p class="color-white bold" style="cursor: pointer;" @click="showReferralModal()">{!! trans('front.string_46') !!}</p>
						</template>
					</div>
					<div class="footer">
						<p>
							<a href="{{ asset('assets/files/terms_'.region().'.pdf') }}" class="term" target="_blank">{!! trans('front.string_47') !!}</a>
						</p>
					</div>
				</div>

			</template>
			<template x-if="page == 3">
				<div class="receipt-container">
					<div class="head">
						<img src="{{ asset('assets/media/icons/stars_white.svg') }}" alt="" class="decoration">
						<h4 class="color-blue">
							{!! trans('front.string_48') !!}
						</h4>
						<img src="{{ asset('assets/media/icons/close-icon_02.svg') }}" alt="" class="close-icon" @click="closeModal()">
					</div>
					<div class="body">
						<p>
							{!! trans('front.string_49') !!}
						</p>
						<img src="{{ asset('assets/media/warning.svg') }}" alt="" class="warning">
						<p>{!! trans('front.string_50') !!}</p>
						<a href="#" class="custom-button" @click.prevent="page = 4">{!! trans('front.string_51') !!}</a>
					</div>
					<div class="footer">
						<p><a href="{{ asset('assets/files/terms_'.region().'.pdf') }}" class="term" target="_blank">{!! trans('front.string_52') !!}</a></p>
					</div>
				</div>
			</template>
			<template x-if="page == 5 || page == 6">
				<div class="receipt-container">
					<div class="head">
						<img src="{{ asset('assets/media/icons/stars_white.svg') }}" alt="" class="decoration">
						<h4 class="color-white">
							{!! trans('front.string_53') !!}
						</h4>
						<img src="{{ asset('assets/media/icons/close-icon_02.svg') }}" alt="" class="close-icon" @click="closeModal()">
					</div>
					<div class="body">
						<template x-if="page == 5">
							<p>
								{!! trans('front.string_54') !!}
							</p>
						</template>
						<img src="{{ asset('assets/media/form_01.svg') }}" alt="" class="warning">
						<template x-if="page == 5">
							<a href="#" class="custom-button" @click.prevent="closeModal()">{!! trans('front.string_55') !!}</a>
						</template>
						<template x-if="page == 6">
							<a href="#" class="custom-button" @click.prevent="closeModal()">{!! trans('front.string_56') !!}</a>
						</template>
					</div>
					<div class="footer">
						<p><a href="{{ asset('assets/files/terms_'.region().'.pdf') }}" class="term" target="_blank">{!! trans('front.string_57') !!}</a></p>
					</div>
				</div>
			</template>

			<template x-if="page == 7">
				<div class="receipt-container">
					<div class="head">
						<img src="{{ asset('assets/media/icons/stars_white.svg') }}" alt="" class="decoration">
						<img src="{{ asset('assets/media/icons/close-icon_02.svg') }}" alt="" class="close-icon" @click="closeModal()">
					</div>
					<div class="body">
						<div id="loadingMessage">🎥 Unable to access video stream (please make sure you have a webcam enabled)</div>
						<canvas id="canvas" hidden></canvas>
						<a href="#" class="button button_custom upload-btn" @click.prevent="page = 4">{!! trans('front.string_78') !!}</a>
					</div>
				</div>
			</template>
		</div>
	</template>

	<style>
		#loadingMessage {
			text-align: center;
			padding: 40px;
			background-color: #eee;
			display: none;
		}

		#canvas {
			width: 100%;
		}
	</style>

	<script src="{{ asset('assets/script/jsQR.js') }}"></script>

	<script>
		function drawLine(begin, end, color) {
			canvas.beginPath();
			canvas.moveTo(begin.x, begin.y);
			canvas.lineTo(end.x, end.y);
			canvas.lineWidth = 4;
			canvas.strokeStyle = color;
			canvas.stroke();
		}

		var video, canvasElement, canvas, loadingMessage, qrUrl;

		var qrCodeDetected = false;
		var videoStream = undefined;
	</script>
</div>