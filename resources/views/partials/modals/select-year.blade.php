<div class="section-date-modal modal-form" id="select-year" x-cloak x-data="{
    showDialog: false,
    birthYear: null,
    init() {
        if (!localStorage.userBirthYear) {
            this.showDialog = true;
            this.disableSite();
        }
    },
    disableSite() {
        document.body.classList.add('disabled');
    },
    enableSite() {
        document.body.classList.remove('disabled');
        this.showDialog = false;
    },
    selectYear() {
        localStorage.setItem('userBirthYear', this.birthYear);
        this.enableSite();
    }
}" x-show="showDialog">
    <div class="wrapper-modal">
        <div class="date-container">
			<div class="head">
				<img src="{{ asset('assets/media/icons/cross.svg') }}" alt="" class="close" @click="showDialog = false">
				<img src="{{ asset('assets/media/icons/star_purple.svg') }}" alt="" class="decoration">
			</div>
			<div class="body">
				<h4>РЕГИСТРИРУЙСЯ, <br> ИГРАЙ И ВЫИГРЫВАЙ <br> ПРИЗЫ!</h4>
				<img src="{{ asset('assets/media/icons/star_purple.svg') }}" alt="" class="decoration">
				<p>В акции могут участвовать <br>только пользователи старше 16 лет</p>
				<div class="custom-select date-select">
					<select placeholder="Год рождения" class="input" x-model="birthYear" id="date-select">
						<option value="0">ВЫБЕРИ ГОД:</option>
						@for ($n = 0; $n < 69; $n++)
                            <option value="{{ 2008 - $n }}">{{ 2008 - $n }}</option>
                        @endfor
					</select>
				</div>
                <button type="button" class="button" @click="selectYear">ПОДТВЕРДИТЬ</button>
				<div class="decorations">
					<img src="{{ asset('assets/media/date_01.svg') }}" alt="" class="decor_01">
					<img src="{{ asset('assets/media/date_01.svg') }}" alt="" class="decor_02">
					<img src="{{ asset('assets/media/date_01.svg') }}" alt="" class="decor_03">
				</div>
			</div>
		</div>
    </div>
	<template x-if="showDialog">
		<div>
			<!-- Custom-select.js -->
			<!-- <script src="{{ asset('assets/script/custom-select.min.js') }}" onload="customSelect('.date-select select')"></script> -->
			<!-- <script type="text/javascript">
				customSelect('.date-select select');
			</script> -->
		</div>
	</template>
	
</div>