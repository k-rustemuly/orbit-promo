<footer class="wrapper-full section-footer">
	<div class="wrapper-fix wrapper-middle wrapper-footer">
		<div class="container-footer">
			<div class="container-footer__column-01">
				<a href="/">
					<img src="{{ asset('assets/media/logotype.svg') }}" alt="Logo">
				</a>
			</div>
			<div class="container-footer__column-02">
				<h4>Сроки акции: 29.01.2024 – 21.04.2024</h4>
				<p>©2023 Mars, Incorporated. Все права защищены. ®TM ORBIT® Торговая марка Mars Incorporated и её филиалов 
				Конфиденциальность | Владелец сайта | Для родителей | Юридические условия | Контакты</p>
			</div>
		</div>
	</div>
</footer>

<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        Alpine.store('modal', {
            signIn: false,
			registration: false
        });
		Alpine.store('user', {
			token: localStorage.getItem('token') || null,
			setToken(token) {
				this.token = token;
				localStorage.setItem('token', token);
			},
			info: JSON.parse(localStorage.getItem('userInfo')) || {},
			setInfo(info) {
				this.info = info;
				localStorage.setItem('userInfo', JSON.stringify(info)); // stringify the info object
			}
		});
		Alpine.store('service', {
			async signIn(data) {
				const response = await fetch('/api/{{ app()->getLocale() }}/signIn', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'Accept': 'application/json',
					},
					body: JSON.stringify(data)
				});

				const result = await response.json();
				
				if(result.success) {
					Alpine.store('user').setToken(result.data.token);
					window.location.href = `/{{ app()->getLocale() }}/profile`;
				}
			},
		});
    });
</script>