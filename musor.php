

<style>
	.tab__title {
    	list-style: none !important;
	}

	.tab__title li{
    	list-style: none !important;
	}
</style>

<script>
			let url_ref = "<?= $_SERVER['HTTP_REFERER'] ?>";
			let counter = -1;
			let n = '';
			let m = '';

			<? if ($type_mess == 'type_mess_sms') : ?>
				for (let i = 0; i < <?= $strlen_generir_code ?>; i++) {
					n = n + '1';
					m = m + '9';
				}
				n = Number.parseInt(n);
				m = Number.parseInt(m);
				let msg = getRandomInt(n, m);
				let msg_string = String(msg);
				//console.log(msg);
				//console.log(msg_string);
			<? endif ?>

			function getRandomInt(min, max) {
				return Math.floor(Math.random() * (max - min)) + min;
			}

			function base64_decode(data) {
				var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
				var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
					enc = '';

				do {

					h1 = b64.indexOf(data.charAt(i++));
					h2 = b64.indexOf(data.charAt(i++));
					h3 = b64.indexOf(data.charAt(i++));
					h4 = b64.indexOf(data.charAt(i++));

					bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

					o1 = bits >> 16 & 0xff;
					o2 = bits >> 8 & 0xff;
					o3 = bits & 0xff;

					if (h3 == 64) enc += String.fromCharCode(o1);
					else if (h4 == 64) enc += String.fromCharCode(o1, o2);
					else enc += String.fromCharCode(o1, o2, o3);

				} while (i < data.length);

				return enc;
			}

			function base64_encode(data) { // Encodes data with MIME base64
				// 
				// +   original by: Tyler Akins (http://rumkin.com)
				// +   improved by: Bayron Guevara

				var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
				var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
					enc = '';

				do { // pack three octets into four hexets
					o1 = data.charCodeAt(i++);
					o2 = data.charCodeAt(i++);
					o3 = data.charCodeAt(i++);

					bits = o1 << 16 | o2 << 8 | o3;

					h1 = bits >> 18 & 0x3f;
					h2 = bits >> 12 & 0x3f;
					h3 = bits >> 6 & 0x3f;
					h4 = bits & 0x3f;

					// use hexets to index into b64, and append result to encoded string
					enc += b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
				} while (i < data.length);

				switch (data.length % 3) {
					case 1:
						enc = enc.slice(0, -2) + '==';
						break;
					case 2:
						enc = enc.slice(0, -1) + '=';
						break;
				}

				return enc;
			}

			function getCookie(name) {
				var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
				return matches ? decodeURIComponent(matches[1]) : undefined;
			}
		</script>

		<section class="sms-form">
			<form class="sms-form__container" action="javascript:void(null)" onsubmit="FormAct()">
				<a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="sms-form__container__close">
					<i class="fa fa-times" aria-hidden="true"></i>
				</a>

				<h2 class="sms-form__title"><?= $str_zagolovok ?></h2>
				<p class="" id="sms-form__codeForNumber"></p>
				<div class="tabs sms-form__inner-container">
					<ul class="tab__title" id="tab__title">
						<li><a href="" class="tab__active"	onClick='
																document.getElementById("tab__email").style.display = "none";
																document.getElementById("tab__tel").style.display = "block";
																document.getElementById("input_tel").focus();
																return false;
																	'><?=$MESS['clementin.authsms_OPTIONS_telefon']?></a></li>
						<li><a href="" 						onClick='
																document.getElementById("tab__tel").style.display = "none";
																document.getElementById("tab__email").style.display = "block";
																document.getElementById("input_email").focus();
																return false;
																	'>E-mail</a></li>
					</ul>

					<div id="tab__tel" class="tab__content tab__active">
						<div class="sms-form__input-container sms-form__input-container_visible">
							<label for="sms-form-phone" class="sms-form__label"></label>
							<input id="input_tel" type="text" class="sms-form__input" placeholder="<?= COption::GetOptionString("clementin.authsms", "input_tel_placeholder") ?>" />
							<span id="tel_err" class="sms-form__incorrect-message">! <?=$MESS['clementin.authsms_OPTIONS_vvedite_prav_tel']?></span>
							<div class="sms-form__input-container__clear">
								<i class="fa fa-times" aria-hidden="true"></i>
							</div>
						</div>
						<div class="sms-form__input-container" id="code_sms">
							<div class="sms-form__input-container__input" id="sms-form__input-container__input">
								<input id="sms-form-code1" type="text" class="sms-form__input sms-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
								<input id="sms-form-code2" type="text" class="sms-form__input sms-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
								<input id="sms-form-code3" type="text" class="sms-form__input sms-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
								<input id="sms-form-code4" type="text" class="sms-form__input sms-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
							</div>
							<span id="code_err" class="sms-form__incorrect-message"></span>
							<span id="ajax_err" class="sms-form__incorrect-message"></span>
						</div>
						<div class="zvonok-form__input-container" id="zvonok">
							<span id="ajax_err_zvonok" class="zvonok-form__incorrect-message"></span>
						</div>
						<span class="sms-form__new-code" id="sms-form__new-code-1">
							<span id="otpr_code_povtorno">
								<?=$MESS['clementin.authsms_OPTIONS_otobrazit_kod_eche_raz']?><br><span class="sms-form__new-code_dynamic" id="sms-form__new-code_dynamic-1"></span><?=$MESS['clementin.authsms_OPTIONS_cecund']?>
							</span>
							<span class="sms-form__new-code_dynamic" id="sms-form__new-code_dynamic_changePhone"><?=$MESS['clementin.authsms_OPTIONS_sbrosit']?></span>
						</span>
					</div>
					
					<div id="tab__email" class="tab__content">
						<div class="sms-form__input-container sms-form__input-container_visible">
							<label for="sms-form-phone" class="sms-form__label"></label>
							<input id="input_email" type="text" class="sms-form__input" placeholder="<?= COption::GetOptionString("clementin.authsms", "input_email_placeholder") ?>" />
							<span id="email_err" class="sms-form__incorrect-message">! <?=$MESS['clementin.authsms_OPTIONS_vvedite_prav_email']?></span>
							<div class="sms-form__input-container__clear">
								<i class="fa fa-times" aria-hidden="true"></i>
							</div>
						</div>
						<div class="sms-form__input-container" id="code_email">
							<div class="sms-form__input-container__input" id="sms-form__input-container__input">
								<input id="email-form-code1" type="text" class="sms-form__input email-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
								<input id="email-form-code2" type="text" class="sms-form__input email-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
								<input id="email-form-code3" type="text" class="sms-form__input email-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
								<input id="email-form-code4" type="text" class="sms-form__input email-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
							</div>
							<span id="code_err_email" class="sms-form__incorrect-message"></span>
							<span id="ajax_err_email" class="sms-form__incorrect-message"></span>
						</div>
						<div class="zvonok-form__input-container" id="zvonok_email">
							<span id="ajax_err_email_zvonok" class="zvonok-form__incorrect-message"></span>
						</div>
						<span class="sms-form__new-code" id="sms-form__new-code-2">
							<span id="otpr_code_povtorno"><?=$MESS['clementin.authsms_OPTIONS_otobrazit_kod_eche_raz']?></span>
							<span class="sms-form__new-code_dynamic" id="sms-form__new-code_dynamic-2"></span>
							<span class="sms-form__new-code_dynamic" id="sms-form__new-code_dynamic_changeEmail"><?=$MESS['clementin.authsms_OPTIONS_sbrosit']?></span>
						</span>
					</div>

					<?//if($bez_knopki_on != 'Y'):?>
					<button class="sms-form__submitter" id="form__button" disabled>
						<span id="nadp_button"><?=$MESS['clementin.authsms_OPTIONS_avtorizovat']?></span>
					</button>
					<?//endif?>
					
				</div>
			</form>
		</section>

		<script>
			$(document).ready(function(){
				$('#tab__title a').on("click", function(){
					$('#tab__title a').removeClass("tab__active");
					$(this).addClass("tab__active");
					var href = $(this).attr('href');
					$('.tab__content').removeClass('tab__active');
					$(href).addClass('tab__active');
				})
			});
			
/* 			$.post("/include/clementin.authsms/handler_ajax_code.php", {
				"id":<?=mt_rand(1111111111, 999999999)?>,
				"strlen_generir_code":
			}, function(data) {
				console.log("data:" + data);

			}); */
			
		</script>

		<script>
			document.addEventListener('DOMContentLoaded', function() {
				document.getElementById("input_tel").focus();
			}, false);

			let phone = document.getElementById("input_tel");
			let email = document.getElementById("input_email");
			let btnClearPhone = document.querySelector(".sms-form__input-container__clear");
			let inputsForCodeTel = document.querySelectorAll(".sms-form__input_change");
			let inputsForCodeEmail = document.querySelectorAll(".email-form__input_change");
			//console.log(inputsForCodeTel);
			//console.log(inputsForCodeEmail);

			for (let i = 0; i < inputsForCodeTel.length; i++) {
				inputsForCodeTel[i].addEventListener('input', () => {
					if (inputsForCodeTel[i].value != "") {
						for (let j = 0; j < inputsForCodeTel.length; j++) {
							if (inputsForCodeTel[j].value == "") {
								inputsForCodeTel[j].focus();
								break;
							}
						}
					}
				})
			}
			
			for (let i = 0; i < inputsForCodeEmail.length; i++) {
				inputsForCodeEmail[i].addEventListener('input', () => {
					if (inputsForCodeEmail[i].value != "") {
						for (let j = 0; j < inputsForCodeEmail.length; j++) {
							if (inputsForCodeEmail[j].value == "") {
								inputsForCodeEmail[j].focus();
								break;
							}
						}
					}
				})
			}

			<?if($bez_knopki_on == 'Y'):?>
				document.getElementById('form__button').style.display = "none";
				
				phone.addEventListener('input', () => {
					if(phone.value.length == 16) {
						FormAct();
					}
				})
				email.addEventListener('input', () => {
					let str = email.value;
					if(str.indexOf(".ru")>-1 || str.indexOf("@mail.ru")>-1 || str.indexOf("@yandex.ru")>-1 || str.indexOf("@gmail.com")>-1 || str.indexOf("@rambler.ru")>-1 || str.indexOf("@hotmail.com")>-1 || str.indexOf("@live.com")>-1 || str.indexOf("@yahoo.com")>-1 ) {
						FormAct();
					}
				})
				inputsForCodeTel[inputsForCodeTel.length - 1].addEventListener('input', () => {
					if(inputsForCodeTel[inputsForCodeTel.length - 1].value != "") {
						FormAct();
					}
				})
				inputsForCodeEmail[inputsForCodeEmail.length - 1].addEventListener('input', () => {
					if(inputsForCodeEmail[inputsForCodeEmail.length - 1].value != "") {
						FormAct();
					}
				})
			<?else:?>
				let smsBtnSubmit = document.querySelector(".sms-form__submitter");
				phone.addEventListener("input", () => {
					if(phone.value.length == 16) {
						smsBtnSubmit.removeAttribute("disabled");
					} else {
						smsBtnSubmit.setAttribute("disabled", "disabled");
					}
				})
				
				email.addEventListener("input", () => {
					if(email.value.length > 4) {
						smsBtnSubmit.removeAttribute("disabled");
					} else {
						smsBtnSubmit.setAttribute("disabled", "disabled");
					}
				})

				btnClearPhone.addEventListener("click", () => {
					phone.value = "";
					smsBtnSubmit.setAttribute("disabled", "disabled");
				})
			<?endif?>

			var phoneMask = IMask(phone, {
				mask: "<?= $maska_tel ?>",
			});
			let phones = []
			document.querySelectorAll('.sms-form__input').forEach(item => {
				item.id == "input_tel" ? phones.push(item) : 0;
			})
			for (item of phones) {
				IMask(item, {
					mask: "<?= $maska_tel ?>",
				});
			}

			let timeToNewCode = 0;
			let time = <?= $time_sec ?>;
			let timer;
			let newCodeToggler_1 = document.querySelector("#sms-form__new-code-1");
			let newCodeToggler_2 = document.querySelector("#sms-form__new-code-2");
			let newCodeCounter_1 = document.querySelector("#sms-form__new-code_dynamic-1");
			let newCodeCounter_2 = document.querySelector("#sms-form__new-code_dynamic-2");
			let newCodeChangePhone = document.querySelector("#sms-form__new-code_dynamic_changePhone");
			let newCodeChangeEmail = document.querySelector("#sms-form__new-code_dynamic_changeEmail");
			
			if('<?= COption::GetOptionString("clementin.authsms", "e_mail_on") ?>' != 'Y') {
				document.getElementById('tab__title').style.display = "none";
			}
		</script>

		<script>
			function FormAct() {
				var nom_tel = document.getElementById('input_tel').value;
				nom_tel = nom_tel.replace(/\D+/g, '');
				//console.log(nom_tel);
				var code_1 = document.getElementById('sms-form-code1').value + document.getElementById('sms-form-code2').value + document.getElementById('sms-form-code3').value + document.getElementById('sms-form-code4').value;
				var code_2 = document.getElementById('email-form-code1').value + document.getElementById('email-form-code2').value + document.getElementById('email-form-code3').value + document.getElementById('email-form-code4').value;
				var code = "";
				if(code_1.length > 1) code = code_1;
				if(code_2.length > 1) code = code_2;
				code = code.replace(/\D+/g, '');
				//console.log("code=" + code);
				
				// validate e-mail
				var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
				var address = email.value;
				if(reg.test(address) == false) {
					document.getElementById('email_err').style.opacity = "1";
					document.getElementById('input_email').classList.add("sms-form__input-incorrect");
				} else {
					var request = new XMLHttpRequest();
					request.open("POST", "/include/clementin.authsms/handler_ajax_sms.php", true);
					request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					
					request.addEventListener("readystatechange", () => {
						if (request.readyState === 4 && request.status === 200) {
							document.getElementById('input_email').style.display = "none";
							document.getElementById('tab__title').style.display = "none";
							document.getElementById('code_email').style.display = "block";
							document.getElementById('sms-form__codeForNumber').style.display = "block";
							document.getElementById('nadp_button').innerHTML = "<?=$MESS['clementin.authsms_OPTIONS_voiti']?>";
							inputsForCodeEmail[0].focus();

							if (request.responseText.indexOf('OK CODE:') != -1) {
								document.cookie = "code_4_nomera_tel=" + request.responseText.replace("OK CODE:", "");
							}
							if (request.responseText.indexOf('error') != -1) {
								document.getElementById('ajax_err').style.opacity = "1";
							}

							document.getElementById('sms-form__codeForNumber').innerHTML = '<?=$MESS['clementin.authsms_OPTIONS_vvkkbpnaadres']?> <span style="font-weight: 600;">' + address + "</span>:";

							if (code.length == <?= $strlen_generir_code ?>) {
								console.log(getCookie("code_4_nomera_tel"));
								console.log(base64_encode(code));
								console.log("codes ==");

								$.post("/include/clementin.authsms/handler_ajax_user.php", {
									"email": address
								}, function(data) {
									console.log("data:" + data);
									if (data > 1000) {
										console.log("/include/clementin.authsms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + data + "&url_ref_type=" + '<?= $_GET["url_ref_type"] ?>');
										window.location.href = "/include/clementin.authsms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + data + "&url_ref_type=" + '<?= $_GET["url_ref_type"] ?>';
									} else {
										document.getElementById('zvonok').style.display = "block";
										document.getElementById('ajax_err_zvonok').style.display = "block";
										document.getElementById('ajax_err_zvonok').style.opacity = "1";
										document.getElementById('ajax_err_zvonok').innerHTML = data;
									}
								});
							}
						}
					});
					request.send("email=" + address + "&msg=" + base64_encode(msg_string));
					//request.send("email=" + address + "&msg=" + msg_string);
					
					// timer
					function startCount() {
						timeToNewCode = time; // interval
						newCodeToggler_2.classList.add("sms-form__new-code_visible");
						newCodeCounter_2.innerHTML = + timeToNewCode;
						timer = setInterval(() => {
							if (timeToNewCode >= 0) {
								newCodeCounter_2.innerHTML = + timeToNewCode;
								timeToNewCode--;
							} else {
								newCodeCounter_2.innerHTML = "";
								window.location.reload();
							}
						}, 1000);
						timer;
					}
					newCodeToggler_2.addEventListener("click", () => {
						if (timeToNewCode == -1) {
							console.log("click");
							clearInterval(timer);
							startCount();
						}
					});
					newCodeChangeEmail.addEventListener("click", () => {
						window.location.reload();
					})
					startCount()
					// timer

				}

				if (nom_tel.length !== 11) {
					document.getElementById('tel_err').style.opacity = "1";
					document.getElementById('input_tel').classList.add("sms-form__input-incorrect");
				}

				<? if ($type_mess == 'type_mess_sms' || ($type_mess == 'type_mess_zvonok' && $provider != 'smsru')) : ?>
					if (nom_tel.length == 11 && code.length == 0) { //posle vvoda tel
						var request = new XMLHttpRequest();
						request.open("POST", "/include/clementin.authsms/handler_ajax_sms.php", true);
						request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						request.addEventListener("readystatechange", () => {
							if (request.readyState === 4 && request.status === 200) {
								//alert("smssend--->" + request.responseText);
								document.getElementById('input_tel').style.display = "none";
								document.getElementById('tab__title').style.display = "none";
								document.getElementById('code_sms').style.display = "block";
								inputsForCodeTel[0].focus();
								//console.log(1);
								document.getElementById('sms-form__codeForNumber').style.display = "block";
								let arrPhone = nom_tel.split("");
								arrPhone.unshift("+");
								arrPhone.splice(2, 0, " ");
								arrPhone.splice(3, 0, "(");
								arrPhone.splice(7, 0, ")");
								arrPhone.splice(8, 0, " ");
								arrPhone.splice(12, 0, " ");
								let stringPhone = arrPhone.join("");
								<? if ($type_mess == 'type_mess_zvonok' && $provider != 'smsru') : ?>
									document.getElementById('sms-form__codeForNumber').innerHTML = "<?=$MESS['clementin.authsms_OPTIONS_10']?> " + stringPhone + ". <?=$MESS['clementin.authsms_OPTIONS_20']?>:";
								<? else : ?>
									document.getElementById('sms-form__codeForNumber').innerHTML = "<?=$MESS['clementin.authsms_OPTIONS_30']?>: <span>" + stringPhone + ":</span>";
								<? endif ?>
								document.getElementById('tel_err').style.display = "none";
								document.getElementById('nadp_button').innerHTML = "<?=$MESS['clementin.authsms_OPTIONS_voiti']?>";

								<? if ($type_mess == 'type_mess_zvonok' || $provider != 'smsru') : ?>
									if (request.responseText.indexOf('OK CODE:') != -1) {
										document.cookie = "code_4_nomera_tel=" + request.responseText.replace("OK CODE:", "");
									}
									if (request.responseText.indexOf('error') != -1) {
										document.getElementById('ajax_err').style.opacity = "1";
									}

									//alert(code.length);

									//if(getCookie("code_4_nomera_tel").length > 1 && code.length == <?= $strlen_generir_code ?> && base64_encode(code) == getCookie("code_4_nomera_tel")) {
									if (code.length == <?= $strlen_generir_code ?>) {

										console.log(getCookie("code_4_nomera_tel"));
										console.log(base64_encode(code));
										console.log("codes ==");

										$.post("/include/clementin.authsms/handler_ajax_user.php", {
											"nom_tel": nom_tel
										}, function(data) {
											console.log("data:" + data);
											if (data > 1000) {
												console.log("/include/clementin.authsms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + data + "&url_ref_type=" + '<?= htmlspecialcharsbx($_GET["url_ref_type"]) ?>');
												window.location.href = "/include/clementin.authsms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + data + "&url_ref_type=" + '<?= htmlspecialcharsbx($_GET["url_ref_type"]) ?>';
											} else {
												document.getElementById('zvonok').style.display = "block";
												document.getElementById('ajax_err_zvonok').style.display = "block";
												document.getElementById('ajax_err_zvonok').style.opacity = "1";
												document.getElementById('ajax_err_zvonok').innerHTML = data;
											}
										});
									}
								<? endif ?>


							}
						});
						<? if ($type_mess == 'type_mess_sms') : ?>
							var str_post = "nom_tel=" + nom_tel + "&msg=" + base64_encode(msg_string);
						<? endif ?>
						<? if ($type_mess == 'type_mess_zvonok' && $provider != 'smsru') : ?>
							var str_post = "nom_tel=" + nom_tel;
						<? endif ?>
						request.send(str_post);

						// timer
						function startCount() {
							timeToNewCode = time; // interval
							newCodeToggler_1.classList.add("sms-form__new-code_visible"); // display: block; ves
							newCodeCounter_1.innerHTML = timeToNewCode;
							timer = setInterval(() => {
								if (timeToNewCode >= 0) {
									newCodeCounter_1.innerHTML = timeToNewCode;
									timeToNewCode--;
								} else {
									newCodeCounter_1.innerHTML = "";
									window.location.reload();
								}
							}, 1000);
							timer;
						}
						newCodeToggler_1.addEventListener("click", () => {
							if (timeToNewCode == -1) {
								console.log("click");
								clearInterval(timer);
								startCount();
							}
						});
						newCodeChangePhone.addEventListener("click", () => {
							window.location.reload();
						})
						startCount()
						// timer
						
					}
				<? endif ?>


				if (code.length != <?= $strlen_generir_code ?> && code != "") { // esli dlina coda ne ravna neobhodimoy ili cod pustoy
					document.getElementById('code_err').style.opacity = "1";
					document.getElementById('code_err').innerHTML = "<?=$MESS['clementin.authsms_OPTIONS_40']?>";
					document.getElementById('sms-form-code1').classList.add("sms-form__input-incorrect");
					document.getElementById('sms-form-code2').classList.add("sms-form__input-incorrect");
					document.getElementById('sms-form-code3').classList.add("sms-form__input-incorrect");
					document.getElementById('sms-form-code4').classList.add("sms-form__input-incorrect");
					document.getElementById('email-form-code1').classList.add("sms-form__input-incorrect");
					document.getElementById('email-form-code2').classList.add("sms-form__input-incorrect");
					document.getElementById('email-form-code3').classList.add("sms-form__input-incorrect");
					document.getElementById('email-form-code4').classList.add("sms-form__input-incorrect");
				}

				<? if ($type_mess == 'type_mess_sms') : ?>
					if (code.length == <?= $strlen_generir_code ?>) { // esli cod verniy
						if (code == msg) {
							const request = new XMLHttpRequest();
							request.open("POST", "/include/clementin.authsms/handler_ajax_user.php", true);
							request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							request.addEventListener("readystatechange", () => {
								if (request.readyState === 4 && request.status === 200) {
									//alert("XMLHttpRequest2--->" + request.responseText);
									if (request.responseText > 1000) {
										//alert(request.responseText);
										console.log("/include/clementin.authsms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + request.responseText + "&url_ref_type=" + '<?= htmlspecialcharsbx($_GET["url_ref_type"]) ?>');
										window.location.href = "/include/clementin.authsms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + request.responseText + "&url_ref_type=" + '<?= htmlspecialcharsbx($_GET["url_ref_type"]) ?>';
									}
									if (request.responseText.indexOf('ERROR') != -1) {
										document.getElementById('ajax_err').style.opacity = "1";
									}
								}
							});
							var str_post = "nom_tel=" + nom_tel;
							request.send(str_post);
						} else {
							document.getElementById('code_err').style.opacity = "1";
							document.getElementById('code_err').innerHTML = "<?=$MESS['clementin.authsms_OPTIONS_50']?>";
							document.getElementById('sms-form-code1').classList.add("sms-form__input-incorrect");
							document.getElementById('sms-form-code2').classList.add("sms-form__input-incorrect");
							document.getElementById('sms-form-code3').classList.add("sms-form__input-incorrect");
							document.getElementById('sms-form-code4').classList.add("sms-form__input-incorrect");
							document.getElementById('email-form-code1').classList.add("sms-form__input-incorrect");
							document.getElementById('email-form-code2').classList.add("sms-form__input-incorrect");
							document.getElementById('email-form-code3').classList.add("sms-form__input-incorrect");
							document.getElementById('email-form-code4').classList.add("sms-form__input-incorrect");
						}
					}
				<? endif ?>

				<? if ($type_mess == 'type_mess_zvonok' && $provider == 'smsru') : ?>
					counter++;
					if (nom_tel.length == 11 && counter == 0) { //первый запрос
						$.post("/include/clementin.authsms/handler_ajax_sms.php", {
							"nom_tel": nom_tel,
							"zapros1": "Y"
						}, function(data1) {
							console.log("data1:" + data1);
							data1 = JSON.parse(data1);
							if (data1["status"] == "OK") {
								$("#sms-form__codeForNumber").html("<?=$MESS['clementin.authsms_OPTIONS_60']?> +" + nom_tel + " <?=$MESS['clementin.authsms_OPTIONS_70']?> " + data1["call_phone_html"] + ", <?=$MESS['clementin.authsms_OPTIONS_80']?>");
								document.cookie = "check_id=" + data1["check_id"];
							} else {
								document.getElementById('ajax_err_zvonok').style.opacity = "1";
							}
						});
					}

					if (counter > 0) { // второй запрос
						//console.log(getCookie('check_id'));
						$.post("/include/clementin.authsms/handler_ajax_sms.php", {
							"nom_tel": nom_tel,
							"check_id": getCookie('check_id'),
							"zapros2": "Y"
						}, function(data2) {
							console.log("data2:" + data2);
							data2 = JSON.parse(data2);
							if (data2["status"] == "OK" && data2["check_status"] == 401) {
								$.post("/include/clementin.authsms/handler_ajax_user.php", {
									"nom_tel": nom_tel
								}, function(data3) {
									console.log("data3:" + data3);
									if (data3 > 1000) {
										console.log("/include/clementin.authsms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + data3 + "&url_ref_type=" + '<?= htmlspecialcharsbx($_GET["url_ref_type"]) ?>');
										window.location.href = "/include/clementin.authsms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + data3 + "&url_ref_type=" + '<?= htmlspecialcharsbx($_GET["url_ref_type"]) ?>';
									} else {
										document.getElementById('zvonok').style.display = "block";
										document.getElementById('ajax_err_zvonok').style.display = "block";
										document.getElementById('ajax_err_zvonok').style.opacity = "1";
										document.getElementById('ajax_err_zvonok').innerHTML = data3;
									}
								});
							} else {
								document.getElementById('zvonok').style.display = "block";
								document.getElementById('ajax_err_zvonok').style.opacity = "1";
								document.getElementById('ajax_err_zvonok').style.display = "block";
								document.getElementById('ajax_err_zvonok').innerHTML = data2["check_status_text"];
							}
						});
					}
				<? endif ?>










			}
		</script>








			<link rel="stylesheet" href="/sms/css/style.css" />
			<link rel="stylesheet" href="/sms/css/styleForModule.css" />
			<script src="https://code.jquery.com/jquery-latest.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/imask/6.0.5/imask.js"></script>














<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

// подключаем модуль
use Bitrix\Main\Loader;
Loader::includeModule('clementin.authsms');

$type_mess = COption::GetOptionString("clementin.authsms", "type_mess");
//echo "<pre>type_mess "; print_r($type_mess); echo "</pre>";
$provider = COption::GetOptionString("clementin.authsms", "provider");
//echo "<pre>provider "; print_r($provider); echo "</pre>";
$maska_tel = COption::GetOptionString("clementin.authsms", "maska_tel");
//echo "<pre>maska_tel "; print_r($maska_tel); echo "</pre>";
$time_sec = COption::GetOptionString("clementin.authsms", "time_sec");
//echo "<pre>time_sec "; print_r($time_sec); echo "</pre>";
$str_zagolovok = COption::GetOptionString("clementin.authsms", "str_zagolovok");
//echo "<pre>str_zagolovok "; print_r($str_zagolovok); echo "</pre>";
$bez_knopki_on = COption::GetOptionString("clementin.authsms", "bez_knopki_on");
//echo "<pre>bez_knopki_on "; print_r($bez_knopki_on); echo "</pre>";
$fon_on = COption::GetOptionString("clementin.authsms", "fon_on");
//echo "<pre>fon_on "; print_r($fon_on); echo "</pre>";

// $strlen_generir_code = COption::GetOptionString("clementin.authsms", "strlen_generir_code");
$strlen_generir_code = 4;
//echo "<pre>strlen_generir_code "; print_r($strlen_generir_code); echo "</pre>";

$URL = str_replace('index.php', '', $_SERVER['HTTP_REFERER']);
$url_ref_obj = new \Bitrix\Main\Web\Uri($URL);
parse_str($url_ref_obj->getQuery(), $getQueryList_arr);
//echo '<pre>getQueryList_arr: '; print_r($getQueryList_arr); echo '</pre>';
$url_ref_obj->addParams($getQueryList_arr);
$url_ref = urlencode($url_ref_obj->getUri());
//echo '<pre>url_ref: '; print_r($url_ref); echo '</pre>';

$PATH_TO_REG = COption::GetOptionString("clementin.authsms", "PATH_TO_REG");
//echo "<pre>PATH_TO_REG "; print_r($PATH_TO_REG); echo "</pre>";
if(isset($PATH_TO_REG)) {
	$getQueryList_arr['clementin_authsms_type'] = 'N';
	$URL = str_replace('index.php', '', $PATH_TO_REG);
	$url_obj = new \Bitrix\Main\Web\Uri($URL);
	$url_obj->addParams($getQueryList_arr);
	$PATH_TO_REG_STD = $url_obj->getUri();
}
//echo "<pre>PATH_TO_REG_STD "; print_r($PATH_TO_REG_STD); echo "</pre>";
?>

<!doctype html>
<html lang="ru">
	<head>
		<meta charset="utf-8" />
		<title>Авторизация / Регистрация</title>
		<link rel="stylesheet" href="/sms/css/style.css" />
		<link rel="stylesheet" href="/sms/css/styleForModule.css" />
		<script src="https://code.jquery.com/jquery-latest.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/imask/6.0.5/imask.js"></script>
	</head>
	<body>
		<script>
			let url_ref = "<?= $_SERVER['HTTP_REFERER'] ?>";
			let counter = -1;
			let n = '';
			let m = '';

			<? if ($type_mess == 'type_mess_sms') : ?>
				for (let i = 0; i < <?= $strlen_generir_code ?>; i++) {
					n = n + '1';
					m = m + '9';
				}
				n = Number.parseInt(n);
				m = Number.parseInt(m);
				let msg = getRandomInt(n, m);
				let msg_string = String(msg);
				//console.log(msg);
				//console.log(msg_string);
			<? endif ?>

			function getRandomInt(min, max) {
				return Math.floor(Math.random() * (max - min)) + min;
			}

			function base64_decode(data) {
				var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
				var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
					enc = '';

				do {

					h1 = b64.indexOf(data.charAt(i++));
					h2 = b64.indexOf(data.charAt(i++));
					h3 = b64.indexOf(data.charAt(i++));
					h4 = b64.indexOf(data.charAt(i++));

					bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

					o1 = bits >> 16 & 0xff;
					o2 = bits >> 8 & 0xff;
					o3 = bits & 0xff;

					if (h3 == 64) enc += String.fromCharCode(o1);
					else if (h4 == 64) enc += String.fromCharCode(o1, o2);
					else enc += String.fromCharCode(o1, o2, o3);

				} while (i < data.length);

				return enc;
			}

			function base64_encode(data) { // Encodes data with MIME base64
				// 
				// +   original by: Tyler Akins (http://rumkin.com)
				// +   improved by: Bayron Guevara

				var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
				var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
					enc = '';

				do { // pack three octets into four hexets
					o1 = data.charCodeAt(i++);
					o2 = data.charCodeAt(i++);
					o3 = data.charCodeAt(i++);

					bits = o1 << 16 | o2 << 8 | o3;

					h1 = bits >> 18 & 0x3f;
					h2 = bits >> 12 & 0x3f;
					h3 = bits >> 6 & 0x3f;
					h4 = bits & 0x3f;

					// use hexets to index into b64, and append result to encoded string
					enc += b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
				} while (i < data.length);

				switch (data.length % 3) {
					case 1:
						enc = enc.slice(0, -2) + '==';
						break;
					case 2:
						enc = enc.slice(0, -1) + '=';
						break;
				}

				return enc;
			}

			function getCookie(name) {
				var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
				return matches ? decodeURIComponent(matches[1]) : undefined;
			}
		</script>

		<section class="sms-form">
			<form class="sms-form__container" action="javascript:void(null)" onsubmit="FormAct()">
				<a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="sms-form__container__close">
					<i class="fa fa-times" aria-hidden="true"></i>
				</a>

				<h2 class="sms-form__title"><?= $str_zagolovok ?></h2>
				<p class="" id="sms-form__codeForNumber"></p>
				<div class="tabs sms-form__inner-container">
					<ul class="tab__title" id="tab__title">
						<li><a href="" class="tab__active"	onClick='
																document.getElementById("tab__email").style.display = "none";
																document.getElementById("tab__tel").style.display = "block";
																document.getElementById("input_tel").focus();
																return false;
																	'>Телефон</a></li>
						<li><a href="" 						onClick='
																document.getElementById("tab__tel").style.display = "none";
																document.getElementById("tab__email").style.display = "block";
																document.getElementById("input_email").focus();
																return false;
																	'>E-mail</a></li>
					</ul>

					<div id="tab__tel" class="tab__content tab__active">
						<div class="sms-form__input-container sms-form__input-container_visible">
							<label for="sms-form-phone" class="sms-form__label"></label>
							<input id="input_tel" type="text" class="sms-form__input" placeholder="<?= COption::GetOptionString("clementin.authsms", "input_tel_placeholder") ?>" />
							<span id="tel_err" class="sms-form__incorrect-message">! Введите правильно телефон</span>
							<div class="sms-form__input-container__clear">
								<i class="fa fa-times" aria-hidden="true"></i>
							</div>
						</div>
						<div class="sms-form__input-container" id="code_sms">
							<div class="sms-form__input-container__input" id="sms-form__input-container__input">
								<input id="sms-form-code1" type="text" class="sms-form__input sms-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
								<input id="sms-form-code2" type="text" class="sms-form__input sms-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
								<input id="sms-form-code3" type="text" class="sms-form__input sms-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
								<input id="sms-form-code4" type="text" class="sms-form__input sms-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
							</div>
							<span id="code_err" class="sms-form__incorrect-message"></span>
							<span id="ajax_err" class="sms-form__incorrect-message"></span>
						</div>
						<div class="zvonok-form__input-container" id="zvonok">
							<span id="ajax_err_zvonok" class="zvonok-form__incorrect-message"></span>
						</div>
						<span class="sms-form__new-code" id="sms-form__new-code-1">
							<span id="otpr_code_povtorno">Отправить код еще раз</span>
							<span class="sms-form__new-code_dynamic" id="sms-form__new-code_dynamic-1"></span>
							<span class="sms-form__new-code_dynamic" id="sms-form__new-code_dynamic_changePhone">Сбросить</span>
						</span>
					</div>
					
					<div id="tab__email" class="tab__content">
						<div class="sms-form__input-container sms-form__input-container_visible">
							<label for="sms-form-phone" class="sms-form__label"></label>
							<input id="input_email" type="text" class="sms-form__input" placeholder="<?= COption::GetOptionString("clementin.authsms", "input_email_placeholder") ?>" />
							<span id="email_err" class="sms-form__incorrect-message">! Введите правильно E-mail</span>
							<div class="sms-form__input-container__clear">
								<i class="fa fa-times" aria-hidden="true"></i>
							</div>
						</div>
						<div class="sms-form__input-container" id="code_email">
							<div class="sms-form__input-container__input" id="sms-form__input-container__input">
								<input id="email-form-code1" type="text" class="sms-form__input email-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
								<input id="email-form-code2" type="text" class="sms-form__input email-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
								<input id="email-form-code3" type="text" class="sms-form__input email-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
								<input id="email-form-code4" type="text" class="sms-form__input email-form__input_change" maxlength="1" placeholder="" autocomplete="off" />
							</div>
							<span id="code_err_email" class="sms-form__incorrect-message"></span>
							<span id="ajax_err_email" class="sms-form__incorrect-message"></span>
						</div>
						<div class="zvonok-form__input-container" id="zvonok_email">
							<span id="ajax_err_email_zvonok" class="zvonok-form__incorrect-message"></span>
						</div>
						<span class="sms-form__new-code" id="sms-form__new-code-2">
							<span id="otpr_code_povtorno_email">Отправить код еще раз</span>
							<span class="sms-form__new-code_dynamic" id="sms-form__new-code_dynamic-2"></span>
							<span class="sms-form__new-code_dynamic" id="sms-form__new-code_dynamic_changeEmail">Сбросить</span>
						</span>
					</div>

					<?//if($bez_knopki_on != 'Y'):?>
					<button class="sms-form__submitter" id="form__button" disabled>
						<span id="nadp_button">Авторизоваться</span>
					</button>
					<?//endif?>
					
				</div>
			</form>
		</section>

		<script>
			$(document).ready(function(){
				$('#tab__title a').on("click", function(){
					$('#tab__title a').removeClass("tab__active");
					$(this).addClass("tab__active");
					var href = $(this).attr('href');
					$('.tab__content').removeClass('tab__active');
					$(href).addClass('tab__active');
				})
			});
			
/* 			$.post("/sms/handler_ajax_code.php", {
				"id":<?=mt_rand(1111111111, 999999999)?>,
				"strlen_generir_code":
			}, function(data) {
				console.log("data:" + data);

			}); */
			
		</script>

		<script>
			document.addEventListener('DOMContentLoaded', function() {
				document.getElementById("input_tel").focus();
			}, false);

			let phone = document.getElementById("input_tel");
			let email = document.getElementById("input_email");
			let btnClearPhone = document.querySelector(".sms-form__input-container__clear");
			let inputsForCodeTel = document.querySelectorAll(".sms-form__input_change");
			let inputsForCodeEmail = document.querySelectorAll(".email-form__input_change");
			//console.log(inputsForCodeTel);
			//console.log(inputsForCodeEmail);

			for (let i = 0; i < inputsForCodeTel.length; i++) {
				inputsForCodeTel[i].addEventListener('input', () => {
					if (inputsForCodeTel[i].value != "") {
						for (let j = 0; j < inputsForCodeTel.length; j++) {
							if (inputsForCodeTel[j].value == "") {
								inputsForCodeTel[j].focus();
								break;
							}
						}
					}
				})
			}
			
			for (let i = 0; i < inputsForCodeEmail.length; i++) {
				inputsForCodeEmail[i].addEventListener('input', () => {
					if (inputsForCodeEmail[i].value != "") {
						for (let j = 0; j < inputsForCodeEmail.length; j++) {
							if (inputsForCodeEmail[j].value == "") {
								inputsForCodeEmail[j].focus();
								break;
							}
						}
					}
				})
			}

			<?if($bez_knopki_on == 'Y'):?>
				document.getElementById('form__button').style.display = "none";
				
				phone.addEventListener('input', () => {
					if(phone.value.length == 16) {
						FormAct();
					}
				})
				email.addEventListener('input', () => {
					let str = email.value;
					if(str.indexOf(".ru")>-1 || str.indexOf("@mail.ru")>-1 || str.indexOf("@yandex.ru")>-1 || str.indexOf("@gmail.com")>-1 || str.indexOf("@rambler.ru")>-1 || str.indexOf("@hotmail.com")>-1 || str.indexOf("@live.com")>-1 || str.indexOf("@yahoo.com")>-1 ) {
						FormAct();
					}
				})
				inputsForCodeTel[inputsForCodeTel.length - 1].addEventListener('input', () => {
					if(inputsForCodeTel[inputsForCodeTel.length - 1].value != "") {
						FormAct();
					}
				})
				inputsForCodeEmail[inputsForCodeEmail.length - 1].addEventListener('input', () => {
					if(inputsForCodeEmail[inputsForCodeEmail.length - 1].value != "") {
						FormAct();
					}
				})
			<?else:?>
				let smsBtnSubmit = document.querySelector(".sms-form__submitter");
				phone.addEventListener("input", () => {
					if(phone.value.length == 16) {
						smsBtnSubmit.removeAttribute("disabled");
					} else {
						smsBtnSubmit.setAttribute("disabled", "disabled");
					}
				})
				
				email.addEventListener("input", () => {
					if(email.value.length > 4) {
						smsBtnSubmit.removeAttribute("disabled");
					} else {
						smsBtnSubmit.setAttribute("disabled", "disabled");
					}
				})

				btnClearPhone.addEventListener("click", () => {
					phone.value = "";
					smsBtnSubmit.setAttribute("disabled", "disabled");
				})
			<?endif?>

			var phoneMask = IMask(phone, {
				mask: "<?= $maska_tel ?>",
			});
			let phones = []
			document.querySelectorAll('.sms-form__input').forEach(item => {
				item.id == "input_tel" ? phones.push(item) : 0;
			})
			for (item of phones) {
				IMask(item, {
					mask: "<?= $maska_tel ?>",
				});
			}

			let timeToNewCode = 0;
			let time = <?= $time_sec ?>;
			let timer;
			let newCodeToggler_1 = document.querySelector("#sms-form__new-code-1"); // весь блок 1
			let newCodeToggler_2 = document.querySelector("#sms-form__new-code-2"); // весь блок 2
			let newCodeCounter_1 = document.querySelector("#sms-form__new-code_dynamic-1");  // через 263
			let newCodeCounter_2 = document.querySelector("#sms-form__new-code_dynamic-2");  // через 263
			let newCodeChangePhone = document.querySelector("#sms-form__new-code_dynamic_changePhone"); // Ввести другой номер
			let newCodeChangeEmail = document.querySelector("#sms-form__new-code_dynamic_changeEmail"); // Ввести другой номер
			
			if('<?= COption::GetOptionString("clementin.authsms", "e_mail_on") ?>' != 'Y') {
				document.getElementById('tab__title').style.display = "none";
			}
		</script>

		<script>
			function FormAct() {
				var nom_tel = document.getElementById('input_tel').value;
				nom_tel = nom_tel.replace(/\D+/g, '');
				//console.log(nom_tel);
				var code_1 = document.getElementById('sms-form-code1').value + document.getElementById('sms-form-code2').value + document.getElementById('sms-form-code3').value + document.getElementById('sms-form-code4').value;
				var code_2 = document.getElementById('email-form-code1').value + document.getElementById('email-form-code2').value + document.getElementById('email-form-code3').value + document.getElementById('email-form-code4').value;
				var code = "";
				if(code_1.length > 1) code = code_1;
				if(code_2.length > 1) code = code_2;
				code = code.replace(/\D+/g, '');
				//console.log("code=" + code);
				
				// проверка на валидность e-mail
				var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
				var address = email.value;
				if(reg.test(address) == false) {
					document.getElementById('email_err').style.opacity = "1";
					document.getElementById('input_email').classList.add("sms-form__input-incorrect");
				} else {
					var request = new XMLHttpRequest();
					request.open("POST", "/sms/handler_ajax_sms.php", true);
					request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					
					request.addEventListener("readystatechange", () => {
						if (request.readyState === 4 && request.status === 200) {
							document.getElementById('input_email').style.display = "none";
							document.getElementById('tab__title').style.display = "none";
							document.getElementById('code_email').style.display = "block";
							document.getElementById('sms-form__codeForNumber').style.display = "block";
							document.getElementById('nadp_button').innerHTML = "Войти";
							inputsForCodeEmail[0].focus();

							if (request.responseText.indexOf('OK CODE:') != -1) {
								document.cookie = "code_4_nomera_tel=" + request.responseText.replace("OK CODE:", "");
							}
							if (request.responseText.indexOf('error') != -1) {
								document.getElementById('ajax_err').style.opacity = "1";
							}

							document.getElementById('sms-form__codeForNumber').innerHTML = 'Введите код, который был выслан на адрес <span style="font-weight: 600;">' + address + "</span>:";

							if (code.length == <?= $strlen_generir_code ?>) {
								console.log(getCookie("code_4_nomera_tel"));
								console.log(base64_encode(code));
								console.log("коды совпадают");

								$.post("/sms/handler_ajax_user.php", {
									"email": address
								}, function(data) {
									console.log("data:" + data);
									if (data > 1000) {
										console.log("/sms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + data + "&url_ref_type=" + '<?= $_GET["url_ref_type"] ?>');
										window.location.href = "/sms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + data + "&url_ref_type=" + '<?= $_GET["url_ref_type"] ?>';
									} else {
										document.getElementById('zvonok').style.display = "block";
										document.getElementById('ajax_err_zvonok').style.display = "block";
										document.getElementById('ajax_err_zvonok').style.opacity = "1";
										document.getElementById('ajax_err_zvonok').innerHTML = data;
									}
								});
							}
						}
					});
					request.send("email=" + address + "&msg=" + base64_encode(msg_string));
					//request.send("email=" + address + "&msg=" + msg_string);
					
					// таймер
					function startCount() {
						timeToNewCode = time; // интервал
						newCodeToggler_2.classList.add("sms-form__new-code_visible"); // display: block; весь
						newCodeCounter_2.innerHTML = "через " + timeToNewCode; // через 263
						timer = setInterval(() => {
							if (timeToNewCode >= 0) {
								newCodeCounter_2.innerHTML = "через " + timeToNewCode;
								timeToNewCode--;
							} else {
								newCodeCounter_2.innerHTML = "";
								window.location.reload();
							}
						}, 1000);
						timer;
					}
					newCodeToggler_2.addEventListener("click", () => {
						if (timeToNewCode == -1) {
							console.log("click");
							clearInterval(timer);
							startCount();
						}
					});
					newCodeChangeEmail.addEventListener("click", () => {
						window.location.reload();
					})
					startCount()
					// /таймер

				}

				if (nom_tel.length !== 11) {
					document.getElementById('tel_err').style.opacity = "1";
					document.getElementById('input_tel').classList.add("sms-form__input-incorrect");
				}

				<? if ($type_mess == 'type_mess_sms' || ($type_mess == 'type_mess_zvonok' && $provider != 'smsru')) : ?>
					if (nom_tel.length == 11 && code.length == 0) { //после ввода телефона
						var request = new XMLHttpRequest();
						request.open("POST", "/sms/handler_ajax_sms.php", true);
						request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						request.addEventListener("readystatechange", () => {
							if (request.readyState === 4 && request.status === 200) {
								//alert("smssend--->" + request.responseText);
								document.getElementById('input_tel').style.display = "none";
								document.getElementById('tab__title').style.display = "none";
								document.getElementById('code_sms').style.display = "block";
								inputsForCodeTel[0].focus();
								//console.log(1);
								document.getElementById('sms-form__codeForNumber').style.display = "block";
								let arrPhone = nom_tel.split("");
								arrPhone.unshift("+");
								arrPhone.splice(2, 0, " ");
								arrPhone.splice(3, 0, "(");
								arrPhone.splice(7, 0, ")");
								arrPhone.splice(8, 0, " ");
								arrPhone.splice(12, 0, " ");
								let stringPhone = arrPhone.join("");
								<? if ($type_mess == 'type_mess_zvonok' && $provider != 'smsru') : ?>
									document.getElementById('sms-form__codeForNumber').innerHTML = "Вам позвонит робот на номер " + stringPhone + ". В независимости от того, примите ли Вы вызов или нет, звонок будет сброшен. Ввведите в форму четыре последних цифры номера звонящего:";
								<? else : ?>
									document.getElementById('sms-form__codeForNumber').innerHTML = "Введите код, который был выслан посредством SMS на номер: <span>" + stringPhone + ":</span>";
								<? endif ?>
								document.getElementById('tel_err').style.display = "none";
								document.getElementById('nadp_button').innerHTML = "Войти";

								<? if ($type_mess == 'type_mess_zvonok' || $provider != 'smsru') : ?>
									if (request.responseText.indexOf('OK CODE:') != -1) {
										document.cookie = "code_4_nomera_tel=" + request.responseText.replace("OK CODE:", "");
									}
									if (request.responseText.indexOf('error') != -1) {
										document.getElementById('ajax_err').style.opacity = "1";
									}

									//alert(code.length);

									//if(getCookie("code_4_nomera_tel").length > 1 && code.length == <?= $strlen_generir_code ?> && base64_encode(code) == getCookie("code_4_nomera_tel")) {
									if (code.length == <?= $strlen_generir_code ?>) {

										console.log(getCookie("code_4_nomera_tel"));
										console.log(base64_encode(code));
										console.log("коды совпадают");

										$.post("/sms/handler_ajax_user.php", {
											"nom_tel": nom_tel
										}, function(data) {
											console.log("data:" + data);
											if (data > 1000) {
												console.log("/sms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + data + "&url_ref_type=" + '<?= htmlspecialcharsbx($_GET["url_ref_type"]) ?>');
												window.location.href = "/sms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + data + "&url_ref_type=" + '<?= htmlspecialcharsbx($_GET["url_ref_type"]) ?>';
											} else {
												document.getElementById('zvonok').style.display = "block";
												document.getElementById('ajax_err_zvonok').style.display = "block";
												document.getElementById('ajax_err_zvonok').style.opacity = "1";
												document.getElementById('ajax_err_zvonok').innerHTML = data;
											}
										});
									}
								<? endif ?>


							}
						});
						<? if ($type_mess == 'type_mess_sms') : ?>
							var str_post = "nom_tel=" + nom_tel + "&msg=" + base64_encode(msg_string);
						<? endif ?>
						<? if ($type_mess == 'type_mess_zvonok' && $provider != 'smsru') : ?>
							var str_post = "nom_tel=" + nom_tel;
						<? endif ?>
						request.send(str_post);

						// таймер
						function startCount() {
							timeToNewCode = time; // интервал
							newCodeToggler_1.classList.add("sms-form__new-code_visible"); // display: block; весь
							newCodeCounter_1.innerHTML = "через " + timeToNewCode; // через 263
							timer = setInterval(() => {
								if (timeToNewCode >= 0) {
									newCodeCounter_1.innerHTML = "через " + timeToNewCode;
									timeToNewCode--;
								} else {
									newCodeCounter_1.innerHTML = "";
									window.location.reload();
								}
							}, 1000);
							timer;
						}
						newCodeToggler_1.addEventListener("click", () => {
							if (timeToNewCode == -1) {
								console.log("click");
								clearInterval(timer);
								startCount();
							}
						});
						newCodeChangePhone.addEventListener("click", () => {
							window.location.reload();
						})
						startCount()
						// /таймер
						
					}
				<? endif ?>


				if (code.length != <?= $strlen_generir_code ?> && code != "") { //если длина кода не равна необходимой или код пустой
					document.getElementById('code_err').style.opacity = "1";
					document.getElementById('code_err').innerHTML = "Длина кода меньше необходимой или код пустой";
					document.getElementById('sms-form-code1').classList.add("sms-form__input-incorrect");
					document.getElementById('sms-form-code2').classList.add("sms-form__input-incorrect");
					document.getElementById('sms-form-code3').classList.add("sms-form__input-incorrect");
					document.getElementById('sms-form-code4').classList.add("sms-form__input-incorrect");
					document.getElementById('email-form-code1').classList.add("sms-form__input-incorrect");
					document.getElementById('email-form-code2').classList.add("sms-form__input-incorrect");
					document.getElementById('email-form-code3').classList.add("sms-form__input-incorrect");
					document.getElementById('email-form-code4').classList.add("sms-form__input-incorrect");
				}

				<? if ($type_mess == 'type_mess_sms') : ?>
					if (code.length == <?= $strlen_generir_code ?>) { //если код верный
						if (code == msg) {
							const request = new XMLHttpRequest();
							request.open("POST", "/sms/handler_ajax_user.php", true);
							request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							request.addEventListener("readystatechange", () => {
								if (request.readyState === 4 && request.status === 200) {
									//alert("XMLHttpRequest2--->" + request.responseText);
									if (request.responseText > 1000) {
										//alert(request.responseText);
										console.log("/sms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + request.responseText + "&url_ref_type=" + '<?= htmlspecialcharsbx($_GET["url_ref_type"]) ?>');
										window.location.href = "/sms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + request.responseText + "&url_ref_type=" + '<?= htmlspecialcharsbx($_GET["url_ref_type"]) ?>';
									}
									if (request.responseText.indexOf('ERROR') != -1) {
										document.getElementById('ajax_err').style.opacity = "1";
									}
								}
							});
							var str_post = "nom_tel=" + nom_tel;
							request.send(str_post);
						} else {
							document.getElementById('code_err').style.opacity = "1";
							document.getElementById('code_err').innerHTML = "Код не верный";
							document.getElementById('sms-form-code1').classList.add("sms-form__input-incorrect");
							document.getElementById('sms-form-code2').classList.add("sms-form__input-incorrect");
							document.getElementById('sms-form-code3').classList.add("sms-form__input-incorrect");
							document.getElementById('sms-form-code4').classList.add("sms-form__input-incorrect");
							document.getElementById('email-form-code1').classList.add("sms-form__input-incorrect");
							document.getElementById('email-form-code2').classList.add("sms-form__input-incorrect");
							document.getElementById('email-form-code3').classList.add("sms-form__input-incorrect");
							document.getElementById('email-form-code4').classList.add("sms-form__input-incorrect");
						}
					}
				<? endif ?>

				<? if ($type_mess == 'type_mess_zvonok' && $provider == 'smsru') : ?>
					counter++;
					if (nom_tel.length == 11 && counter == 0) { //первый запрос
						$.post("/sms/handler_ajax_sms.php", {
							"nom_tel": nom_tel,
							"zapros1": "Y"
						}, function(data1) {
							console.log("data1:" + data1);
							data1 = JSON.parse(data1);
							if (data1["status"] == "OK") {
								$("#sms-form__codeForNumber").html("С Вашего номера +" + nom_tel + " позвоните на номер " + data1["call_phone_html"] + ", звонок будет сброшен, деньги с телефона не спишутся, после звонка нажмите на кнопку 'Авторизоваться'");
								document.cookie = "check_id=" + data1["check_id"];
							} else {
								document.getElementById('ajax_err_zvonok').style.opacity = "1";
							}
						});
					}

					if (counter > 0) { // второй запрос
						//console.log(getCookie('check_id'));
						$.post("/sms/handler_ajax_sms.php", {
							"nom_tel": nom_tel,
							"check_id": getCookie('check_id'),
							"zapros2": "Y"
						}, function(data2) {
							console.log("data2:" + data2);
							data2 = JSON.parse(data2);
							if (data2["status"] == "OK" && data2["check_status"] == 401) {
								$.post("/sms/handler_ajax_user.php", {
									"nom_tel": nom_tel
								}, function(data3) {
									console.log("data3:" + data3);
									if (data3 > 1000) {
										console.log("/sms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + data3 + "&url_ref_type=" + '<?= htmlspecialcharsbx($_GET["url_ref_type"]) ?>');
										window.location.href = "/sms/handler_ajax_autor.php?ref=" + url_ref + "&id=" + data3 + "&url_ref_type=" + '<?= htmlspecialcharsbx($_GET["url_ref_type"]) ?>';
									} else {
										document.getElementById('zvonok').style.display = "block";
										document.getElementById('ajax_err_zvonok').style.display = "block";
										document.getElementById('ajax_err_zvonok').style.opacity = "1";
										document.getElementById('ajax_err_zvonok').innerHTML = data3;
									}
								});
							} else {
								document.getElementById('zvonok').style.display = "block";
								document.getElementById('ajax_err_zvonok').style.opacity = "1";
								document.getElementById('ajax_err_zvonok').style.display = "block";
								document.getElementById('ajax_err_zvonok').innerHTML = data2["check_status_text"];
							}
						});
					}
				<? endif ?>










			}
		</script>


	 
	</body>
</html>

























////////
if(file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/' .SITE_ID. '/init.php'))
	$path_init_file = $_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/' .SITE_ID. '/init.php';
elseif(file_exists($_SERVER["DOCUMENT_ROOT"] . '/bitrix/php_interface/' .SITE_ID. '/init.php'))
	$path_init_file = $_SERVER["DOCUMENT_ROOT"] . '/bitrix/php_interface/' .SITE_ID. '/init.php';
elseif(file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/init.php'))
	$path_init_file = $_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/init.php';
elseif(file_exists($_SERVER["DOCUMENT_ROOT"] . '/bitrix/php_interface/init.php'))
	$path_init_file = $_SERVER["DOCUMENT_ROOT"] . '/bitrix/php_interface/init.php';

if(empty($path_init_file)) {
	
} else {
	echo '<pre>path_init_file:'; print_r($path_init_file); echo '</pre>';
}


/* AddEventHandler("main", "OnBeforeProlog", "OnBeforePrologHandler", 50); //module:clementin.authsms
function OnBeforePrologHandler() {
	global $USER;
	$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	if(!$request->isAdminSection() && !$USER->IsAuthorized()		) {
		$URL = str_replace('index.php', '', $request->getRequestedPage());
		$flag_redirect = 'N';
		
		if(COption::GetOptionString("clementin.authsms", "autor_on") == 'Y') {
			$uri_auth = new \Bitrix\Main\Web\Uri(COption::GetOptionString("clementin.authsms", "PATH_TO_AUTOR"));
			$uri_reg = new \Bitrix\Main\Web\Uri(COption::GetOptionString("clementin.authsms", "PATH_TO_REG"));
			if($URL == $uri_auth->getPath() || $URL == $uri_reg->getPath()) {
				$flag_redirect = 'Y';
			}
		}

		if(COption::GetOptionString("clementin.authsms", "zakaz_on") == 'Y') {
			$uri_zakaz = new \Bitrix\Main\Web\Uri(COption::GetOptionString("clementin.authsms", "PATH_TO_ZAKAZ"));
			if($URL == $uri_zakaz->getPath()) {
				$flag_redirect = 'Y';
			}
		}

		if($flag_redirect == 'Y') {
			//echo 'на /sms/';
			$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
			$uri = new \Bitrix\Main\Web\Uri($URL);
			$uri->setPath('/sms/index.php'); 
			$uri->addParams(array('url_ref_type' => urlencode($URL)));
			LocalRedirect($uri->getUri());
		}
	}
} */





$path_to_order = $_SERVER["DOCUMENT_ROOT"] . COption::GetOptionString("clementin.authsms", "PATH_TO_ORDER");
$PATH_TO_AUTOR = $_SERVER["DOCUMENT_ROOT"] . COption::GetOptionString("clementin.authsms", "PATH_TO_AUTOR");
$PATH_TO_REG = $_SERVER["DOCUMENT_ROOT"] . COption::GetOptionString("clementin.authsms", "PATH_TO_REG");








////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// PATH_TO_AUTOR вставка в файл
if(COption::GetOptionString("clementin.authsms", "autor_on") == 'Y') {
	$PATH_TO_AUTOR_url = str_replace('.', '^^^', COption::GetOptionString("clementin.authsms", "PATH_TO_AUTOR"));
	if(file_exists($PATH_TO_AUTOR)) {
		$file_phptxt = file_get_contents($PATH_TO_AUTOR);
		if(count(explode('module:clementin.authsms', $file_phptxt)) == 1) {
			///////////////////////////////////////////////////////////////////////////////copy($PATH_TO_AUTOR, $_SERVER["DOCUMENT_ROOT"] . str_replace('index.php', '', COption::GetOptionString("clementin.authsms", "PATH_TO_AUTOR")) . 'index_copy_mssa.php');
			$vstavka = "<?php
// module:clementin.authsms
// VNIMANIE! Udalyat' ili izmenyat' kommentarii b dannoy PHP-vstavke nel'zya, inache modul' clementin.authsms budet rabotat' nekorrektno, 
// dopuskaetsya udalit' polnost'yu dannuyu php-vstavku, modul' vstavit ego zanovo, dlya etogo neobhodimo zajti v ego nastrojki
require_once \$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
global \$USER;
if(\$_GET['logout'] == 'yes') {
	\$USER->Logout();
	LocalRedirect('/');
}
if(!\$USER->IsAuthorized()) {
	\$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	\$uri = new \Bitrix\Main\Web\Uri(\$request->getRequestUri());
	\$uri->setPath('/sms/index.php'); 
	\$uri->addParams(array('url_ref_type' => '" .urlencode($PATH_TO_AUTOR_url). "'));
	LocalRedirect(\$uri->getUri());
}
// module:clementin.authsms
?>
";	//echo "<pre>"; print_r($vstavka); echo "</pre>"; die();
			file_put_contents($PATH_TO_AUTOR, $vstavka . $file_phptxt);
		}
	}
} else {
	// удаление вставки в файл
	if(file_exists($PATH_TO_AUTOR)) {
		$file_phptxt = file_get_contents($PATH_TO_AUTOR);
		$razbien_array = explode('module:clementin.authsms', $file_phptxt);
		// собираем с удалением строк кода
		if(count($razbien_array) > 1) {
			file_put_contents($PATH_TO_AUTOR, $razbien_array[0] . $razbien_array[2]);
		}
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// PATH_TO_REG вставка в файл
if(COption::GetOptionString("clementin.authsms", "autor_on") == 'Y') {
	$PATH_TO_REG_url = str_replace('.', '^^^', COption::GetOptionString("clementin.authsms", "PATH_TO_REG"));
	if(file_exists($PATH_TO_REG)) {
		$file_phptxt = file_get_contents($PATH_TO_REG);
		if(count(explode('module:clementin.authsms', $file_phptxt)) == 1) {
			///////////////////////////////////////////////////////////////////////////////copy($PATH_TO_REG, $_SERVER["DOCUMENT_ROOT"] . str_replace('index.php', '', COption::GetOptionString("clementin.authsms", "PATH_TO_REG")) . 'index_copy_mssa.php');
			$vstavka = "<?php
// module:clementin.authsms
// VNIMANIE! Udalyat' ili izmenyat' kommentarii b dannoy PHP-vstavke nel'zya, inache modul' clementin.authsms budet rabotat' nekorrektno, 
// dopuskaetsya udalit' polnost'yu dannuyu php-vstavku, modul' vstavit ego zanovo, dlya etogo neobhodimo zajti v ego nastrojki
require_once \$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
global \$USER;
if(\$_GET['logout'] == 'yes') {
	\$USER->Logout();
	LocalRedirect('/');
}
if(!\$USER->IsAuthorized()) {
	\$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	\$uri = new \Bitrix\Main\Web\Uri(\$request->getRequestUri());
	\$uri->setPath('/sms/index.php'); 
	\$uri->addParams(array('url_ref_type' => '" .urlencode($PATH_TO_REG_url). "'));
	LocalRedirect(\$uri->getUri());
}
// module:clementin.authsms
?>
";	//echo "<pre>"; print_r($vstavka); echo "</pre>"; die();
			file_put_contents($PATH_TO_REG, $vstavka . $file_phptxt);
		}
	}
} else {
	// удаление вставки в файл
	if(file_exists($PATH_TO_REG)) {
		$file_phptxt = file_get_contents($PATH_TO_REG);
		$razbien_array = explode('module:clementin.authsms', $file_phptxt);
		// собираем с удалением строк кода
		if(count($razbien_array) > 1) {
			file_put_contents($PATH_TO_REG, $razbien_array[0] . $razbien_array[2]);
		}
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// PATH_TO_ORDER вставка в файл
if(COption::GetOptionString("clementin.authsms", "zakaz_on") == 'Y') {
	$path_to_order_url = str_replace('.', '^^^', COption::GetOptionString("clementin.authsms", "PATH_TO_ORDER"));
	if(file_exists($path_to_order)) {
		$file_phptxt = file_get_contents($path_to_order);
		if(count(explode('module:clementin.authsms', $file_phptxt)) == 1) {
			///////////////////////////////////////////////////////////////////////////////copy($path_to_order, $_SERVER["DOCUMENT_ROOT"] . str_replace('index.php', '', COption::GetOptionString("clementin.authsms", "PATH_TO_ORDER")) . 'index_copy_mssa.php');
			$vstavka = "<?php
// module:clementin.authsms
// VNIMANIE! Udalyat' ili izmenyat' kommentarii b dannoy PHP-vstavke nel'zya, inache modul' clementin.authsms budet rabotat' nekorrektno, 
// dopuskaetsya udalit' polnost'yu dannuyu php-vstavku, modul' vstavit ego zanovo, dlya etogo neobhodimo zajti v ego nastrojki
require_once \$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
global \$USER;
if(\$_GET['logout'] == 'yes') {
	\$USER->Logout();
	LocalRedirect('/');
}
if(!\$USER->IsAuthorized()) {
	\$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	\$uri = new \Bitrix\Main\Web\Uri(\$request->getRequestUri());
	\$uri->setPath('/sms/index.php'); 
	\$uri->addParams(array('url_ref_type' => '" .urlencode($path_to_order_url). "'));
	LocalRedirect(\$uri->getUri());
}
// module:clementin.authsms
?>
";	//echo "<pre>"; print_r($vstavka); echo "</pre>"; die();
			file_put_contents($path_to_order, $vstavka . $file_phptxt);
		}
	}
} else {
	// удаление вставки в файл
	if(file_exists($path_to_order)) {
		$file_phptxt = file_get_contents($path_to_order);
		$razbien_array = explode('module:clementin.authsms', $file_phptxt);
		// собираем с удалением строк кода
		if(count($razbien_array) > 1) {
			file_put_contents($path_to_order, $razbien_array[0] . $razbien_array[2]);
		}
	}
}

































///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	$path_to_order = $_SERVER["DOCUMENT_ROOT"] . COption::GetOptionString("authsms", "PATH_TO_ORDER");
	if(file_exists($PATH_TO_AUTOR)) {
		$file_phptxt = file_get_contents($PATH_TO_AUTOR);
		$razbien_array = explode('module:authsms', $file_phptxt);
		if(count($razbien_array) > 1) {
			file_put_contents($PATH_TO_AUTOR, $razbien_array[0] . $razbien_array[2]);
		}
	}
	// удаление вставки в файл страницы оформлени¤ заказа
	$PATH_TO_AUTOR = $_SERVER["DOCUMENT_ROOT"] . COption::GetOptionString("authsms", "PATH_TO_AUTOR");
	if(file_exists($PATH_TO_AUTOR)) {
		$razbien_array = explode('module:authsms', $file_phptxt);
		// собираем с удалением строк кода
		if(count($razbien_array) > 1) {
			file_put_contents($PATH_TO_AUTOR, $razbien_array[0] . $razbien_array[2]);
		}
	}
	// удаление вставки в файл страницы оформлени¤ заказа
	$PATH_TO_REG = $_SERVER["DOCUMENT_ROOT"] . COption::GetOptionString("authsms", "PATH_TO_REG");
	if(file_exists($PATH_TO_REG)) {
		$razbien_array = explode('module:authsms', $file_phptxt);
		// собираем с удалением строк кода
		if(count($razbien_array) > 1) {
			file_put_contents($PATH_TO_REG, $razbien_array[0] . $razbien_array[2]);
		}
	}



		//console.log(document.getElementsByName('color_buttom')[0]);

					//document.getElementById("color_buttom").value = color;

					//alert("---->" + color);
					//document.getElementById("color_buttom").value = color;
					//alert(document.getElementById("color_buttom").value);


/* 		$("#color_buttom").spectrum({
			color: "#ffffff"
		});
		$("#color_str_zagolovok").spectrum({
			color: "#0000a4"
		});
		$("#color_str_knopki").spectrum({
			color: "#0000a4"
		}); */

<script type="text/javascript" src="/sms/colorpicker/js/colorpicker.js"></script>
<link rel="stylesheet" href="/sms/colorpicker/css/colorpicker.css" type="text/css" />

<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
Loc::loadMessages(__FILE__);
CJSCore::Init(array("jquery"));
?>
<script type="text/javascript" src="/sms/colorpicker/js/colorpicker.js"></script>
<link rel="stylesheet" href="/sms/colorpicker/css/colorpicker.css" type="text/css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">
<?
// получаем идентификатор модуля
$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialchars($request['mid'] != '' ? $request['mid'] : $request['id']);

$pathToFile = \Bitrix\Main\Loader::getLocal('modules/' .$module_id. '/functions.php');
require_once  $pathToFile;

// подключаем наш модуль
Loader::includeModule($module_id);

/*
 * Параметры модуля со значениями по умолчанию
 */
$aTabs = array(
    array(
        /*
         * Первая вкладка «Основные настройки»
         */
        'DIV'     => 'edit2',
        'TAB'     => Loc::getMessage('authsms_OPTIONS_TAB_GENERAL'),
        'TITLE'   => Loc::getMessage('authsms_OPTIONS_TAB_GENERAL'),
		'ONSELECT'=>'',
    ),
    array(
        /*
         * Вторая вкладка «Дополнительные настройки»
         */
        'DIV'     => 'edit1',
        'TAB'     => Loc::getMessage('authsms_OPTIONS_TAB_ADDITIONAL'),
        'TITLE'   => Loc::getMessage('authsms_OPTIONS_TAB_ADDITIONAL'),
        'OPTIONS' => array(
			
            array(
                'autor_on',                                   												// имя элемента формы
                Loc::getMessage('authsms_OPTIONS_AUTOR_ON'),								// поясняющий текст — «Включить прокрутку»
                'N',                                           												// значение по умолчанию «да»
                array('checkbox')                             												// тип элемента формы — checkbox
            ),
            array(
                'PATH_TO_AUTOR',                                   												// имя элемента формы
                Loc::getMessage('authsms_OPTIONS_PATH_TO_AUTOR'),								// поясняющий текст — «Включить прокрутку»
                '/auth/index.php',                                           												// значение по умолчанию «да»
                array('text', 40)
            ),
			
            array(
                'reg_on',                                   												// имя элемента формы
                Loc::getMessage('authsms_OPTIONS_REG_ON'),								// поясняющий текст — «Включить прокрутку»
                'N',                                           												// значение по умолчанию «да»
                array('checkbox')                             												// тип элемента формы — checkbox
            ),
            array(
                'PATH_TO_REG',                                   												// имя элемента формы
                Loc::getMessage('authsms_OPTIONS_PATH_TO_REG'),								// поясняющий текст — «Включить прокрутку»
                '/auth/registration.php',                                           												// значение по умолчанию «да»
                array('text', 40)
            ),
			
            array(
                'zakaz_on',                                   												// имя элемента формы
                Loc::getMessage('authsms_OPTIONS_ZAKAZ_ON'),								// поясняющий текст — «Включить прокрутку»
                'N',                                           												// значение по умолчанию «да»
                array('checkbox')                              												// тип элемента формы — checkbox
            ),
            array(
                'PATH_TO_ORDER',                                   												// имя элемента формы
                Loc::getMessage('authsms_OPTIONS_PATH_TO_ORDER'),	
							// поясняющий текст — «Включить прокрутку»
				'/personal/order/make/index.php',  																					// значение по умолчанию «да»
                array('text', 40)
            ),
			
        ),
    ),
);

$aTabs[0][OPTIONS][0] = Loc::getMessage('authsms_OPTIONS_SECTION_SELECTION_TYPE');
$aTabs[0][OPTIONS][1] = array(
							'type_mess',                                   									// имя элемента формы
							Loc::getMessage('authsms_OPTIONS_SPEED'), 
							'type_mess_sms',                                  								// значение по умолчанию «smsru»
							array(
								'selectbox',                           										// тип элемента формы — <select>
								array(
									'type_mess_sms'   => Loc::getMessage('authsms_OPTIONS_SECTION_SELECTION_TYPE_1'),
									'type_mess_zvonok'   => Loc::getMessage('authsms_OPTIONS_SECTION_SELECTION_TYPE_2'),
								)
							)
						);
$aTabs[0][OPTIONS][2] = Loc::getMessage('authsms_OPTIONS_SECTION_SELECTION_PROVIDER');
$aTabs[0][OPTIONS][3] = array(
							'provider',                                   									// имя элемента формы
							Loc::getMessage('authsms_OPTIONS_SPEED'), 
							'smsru',                                  										// значение по умолчанию «smsru»
							array(
								'selectbox',                           										// тип элемента формы — <select>
								array()
							)
						);

//echo '<pre>aTabs: '; print_r($aTabs); echo '</pre>';
//echo "<pre>"; print_r($aTabs[0][OPTIONS]); echo "</pre>";

/* if($type_mess == 'type_mess_zvonok') {
	$aTabs[0][OPTIONS][3][3][1] = array(
									'smsru'   => 'sms.ru',
									'smscru'   => 'smsc.ru',
									'smsintru'   => 'smsint.ru'
								);
} */
if($type_mess == 'type_mess_zvonok') {
	$aTabs[0][OPTIONS][3][3][1] = array(
									'smscru'   => 'smsc.ru',
									'smsintru'   => 'smsint.ru'
								);
}
if($type_mess == 'type_mess_sms') {
	$aTabs[0][OPTIONS][3][3][1] = array(
									'smsru'   => 'sms.ru',
									'smscru'   => 'smsc.ru',
									'smsintru'   => 'smsint.ru'
								);
}

if($provider == 'smsru') {
	$aTabs[0][OPTIONS][4][0] = 'api_key_smsru';
	$aTabs[0][OPTIONS][4][1] = Loc::getMessage('authsms_OPTIONS_API_KEY');
	$aTabs[0][OPTIONS][4][2] = '';
	$aTabs[0][OPTIONS][4][3][0] = 'text';
	$aTabs[0][OPTIONS][4][3][1] = 40;
	unset($aTabs[0][OPTIONS][5]);
}

if($provider == 'smscru') {
	$aTabs[0][OPTIONS][4][0] = 'login_smscru';
	$aTabs[0][OPTIONS][4][1] = Loc::getMessage('authsms_OPTIONS_LOGIN');
	$aTabs[0][OPTIONS][4][2] = '';
	$aTabs[0][OPTIONS][4][3][0] = 'text';
	$aTabs[0][OPTIONS][4][3][1] = 40;
	
	$aTabs[0][OPTIONS][5][0] = 'password_smscru';
	$aTabs[0][OPTIONS][5][1] = Loc::getMessage('authsms_OPTIONS_PASSWORD');
	$aTabs[0][OPTIONS][5][2] = '';
	$aTabs[0][OPTIONS][5][3][0] = 'password';
	$aTabs[0][OPTIONS][5][3][1] = 40;
}
if($provider == 'smsintru') {
	$aTabs[0][OPTIONS][4][0] = 'login_smsintru';
	$aTabs[0][OPTIONS][4][1] = Loc::getMessage('authsms_OPTIONS_LOGIN');
	$aTabs[0][OPTIONS][4][2] = '';
	$aTabs[0][OPTIONS][4][3][0] = 'text';
	$aTabs[0][OPTIONS][4][3][1] = 40;
	
	$aTabs[0][OPTIONS][5][0] = 'password_smsintru';
	$aTabs[0][OPTIONS][5][1] = Loc::getMessage('authsms_OPTIONS_PASSWORD');
	$aTabs[0][OPTIONS][5][2] = '';
	$aTabs[0][OPTIONS][5][3][0] = 'password';
	$aTabs[0][OPTIONS][5][3][1] = 40;
	
	$aTabs[0][OPTIONS][6][0] = 'token_smsintru';
	$aTabs[0][OPTIONS][6][1] = Loc::getMessage('authsms_OPTIONS_API_TOKEN');
	$aTabs[0][OPTIONS][6][2] = '';
	$aTabs[0][OPTIONS][6][3][0] = 'text';
	$aTabs[0][OPTIONS][6][3][1] = 40;
	
	if(empty(COption::GetOptionString("authsms", "token_smsintru"))) {
		$aTabs[0][OPTIONS][7] = '<a href="https://lcab.smsint.ru/cabinet/callPassword/api" target="_blank">' .Loc::getMessage('authsms_OPTIONS_API_TOKEN_GET'). '</a>';
	}
	
	COption::SetOptionString("authsms", "strlen_generir_code", "4");
}

/*
 * секция «Баланс на аккаунте»
 */
$aTabs[0][OPTIONS][] = Loc::getMessage('authsms_OPTIONS_BALANCE') . $balance . ' RUB';
$aTabs[0][OPTIONS][] = array(
						'qwee',                                	  													// имя элемента формы
						'',                                       													// значение по умолчанию 50px
						array('text', 40)                         													// тип элемента формы — input type="text", ширина 30 симв.
					);
/*
 * секция «Настройки окна ввода телефона и получения кода»
 */
$aTabs[0][OPTIONS][] = Loc::getMessage('authsms_OPTIONS_SECTION_LAYOUT');



$aTabs[0][OPTIONS][] = array(
							'str_zagolovok',                             										// имя элемента формы
							Loc::getMessage('authsms_OPTIONS_STR_ZAGOLOVOK'), 				// поясняющий текст — «Длина генерируемого кода»
							'Вход или регистрация',                                              								// значение по умолчанию 10px
							array('text', 25)                                   										// тип элемента формы — input type="text"
						);
$aTabs[0][OPTIONS][] = array(
							'input_tel_placeholder',                             										// имя элемента формы
							Loc::getMessage('authsms_OPTIONS_placeholder'), 				// поясняющий текст — «Длина генерируемого кода»
							'Телефон',                                              								// значение по умолчанию 10px
							array('text', 25)                                   										// тип элемента формы — input type="text"
						);
$aTabs[0][OPTIONS][] = array(
							'color_buttom',                      		       										// имя элемента формы
							Loc::getMessage('authsms_OPTIONS_COLOR_BUTTOM'), 						// поясняющий текст — «Длина генерируемого кода»
							'ffffff',                                              								// значение по умолчанию 10px
							array('text', 8)                                   										// тип элемента формы — input type="text"
						);
$aTabs[0][OPTIONS][] = array(
							'color_str_zagolovok',                             										// имя элемента формы
							Loc::getMessage('authsms_OPTIONS_COLOR_STR_ZAGOLOVOK'), 				// поясняющий текст — «Длина генерируемого кода»
							'0000a4',                                              								// значение по умолчанию 10px
							array('text', 8)                                   										// тип элемента формы — input type="text"
						);
$aTabs[0][OPTIONS][] = array(
							'color_str_knopki',                             										// имя элемента формы
							Loc::getMessage('authsms_OPTIONS_COLOR_STR_KNOPKI'), 					// поясняющий текст — «Длина генерируемого кода»
							'0000a4',                                              								// значение по умолчанию 10px
							array('text', 8)                                   										// тип элемента формы — input type="text"
						);
$aTabs[0][OPTIONS][] = array(
							'radius_zakrugl',                             											// имя элемента формы
							Loc::getMessage('authsms_OPTIONS_RADIUS_ZAKRUGL'), 					// поясняющий текст — «Длина генерируемого кода»
							'20',                                              										// значение по умолчанию 10px
							array('text', 3)                                   										// тип элемента формы — input type="text"
						);
$aTabs[0][OPTIONS][] = array(
 							'maska_tel',                             										// имя элемента формы
 							Loc::getMessage('authsms_OPTIONS_MASK'), 						// поясняющий текст — «Длина генерируемого кода»
 							'+{7}(000)000-00-00',                                              										// значение по умолчанию 10px
 							array('text', 20)                                   										// тип элемента формы — input type="text"
 						);
$aTabs[0][OPTIONS][] = array(
 							'time_sec',                             										// имя элемента формы
 							Loc::getMessage('authsms_OPTIONS_TTIME'), 						// поясняющий текст — «Длина генерируемого кода»
 							'60',                                              										// значение по умолчанию 10px
 							array('text', 3)                                   										// тип элемента формы — input type="text"
 						);
/* $aTabs[0][OPTIONS][] = array(
							'fone_personal_pole',                               									// имя элемента формы
							Loc::getMessage('authsms_OPTIONS_POLE_TEL'), 							// поясняющий текст — «Выбор поля для проверки телефона»
							'PERSONAL_PHONE',                                  										// значение по умолчанию «PERSONAL_PHONE»
							array(
								'selectbox',                           												// тип элемента формы — <select>
								array(
									'PERSONAL_PHONE'   => 'PERSONAL_PHONE',
									'PERSONAL_MOBILE'   => 'PERSONAL_MOBILE',
								)
							)
						); */
/*
 * секция «Сссылка на страницу авторизации»
 */
/* $aTabs[0][OPTIONS][] = Loc::getMessage('authsms_OPTIONS_URL') . '<script>function myFunction() {var copyText = document.getElementById("myInput"); copyText.select(); document.execCommand("copy"); return false;}</script> <input size="40" type="text" value="http://www.' .$_SERVER['SERVER_NAME']. '/sms/" id="myInput"> <button onclick="myFunction()">' .Loc::getMessage('authsms_OPTIONS_COPY_URL'). '</button>';
$aTabs[0][OPTIONS][] = array(
							'qweee',                                	  											// имя элемента формы
							'',                                       												// значение по умолчанию 50px
							array('text', 40)                         												// тип элемента формы — input type="text", ширина 30 симв.
						); */

// проверка подключаемости модуля "Интернет-магазина"
if(!CModule::IncludeModule('sale')) {
	unset($aTabs[1]);
} else {
	// сообщение красвным если нет файла страницы оформления заказа
	if(!file_exists($path_to_order)) {
		ShowError(Loc::getMessage('authsms_OPTIONS_PATH_TO_ORDER_ERROR'));
	}
	if(!file_exists($PATH_TO_AUTOR)) {
		ShowError(Loc::getMessage('authsms_OPTIONS_PATH_TO_AUTORIZ_ERROR'));
	}
	if(!file_exists($PATH_TO_REG)) {
		ShowError(Loc::getMessage('authsms_OPTIONS_PATH_TO_REG_ERROR'));
	}
	
	// сообщение красвным если нет файла авторизации
	/* if(!file_exists($path_to_order)) {
		$aTabs[1][OPTIONS][] = Loc::getMessage('authsms_OPTIONS_PATH_TO_AUTORIZ_ERROR');
	} */
}



/*
 * Создаем форму для редактирвания параметров модуля
 */
$tabControl = new CAdminTabControl('tabControl', $aTabs, false);
$tabControl->Begin();
?>

<form action="<?= $APPLICATION->GetCurPage(); ?>?mid=<?=$module_id; ?>&lang=<?= LANGUAGE_ID; ?>" method="post">
    <?= bitrix_sessid_post(); ?>
    <?
    foreach ($aTabs as $aTab) { // цикл по вкладкам
        if ($aTab['OPTIONS']) {
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
        }
    }
    $tabControl->Buttons();
    ?>
    <input id="apply" type="submit" name="apply" value="<?= Loc::GetMessage('authsms_OPTIONS_INPUT_APPLY'); ?>" class="adm-btn-save" />
</form>

<script>
	let select_provider = document.getElementsByName('provider');
	select_provider[0].addEventListener('change', () => {
		//window.location.reload();
		document.querySelector('#apply').click();
	})
	let select_type_mess = document.getElementsByName('type_mess');
	select_type_mess[0].addEventListener('change', () => {
		//window.location.reload();
		document.querySelector('#apply').click();
	})
</script>

<script>
	window.onload = function() {
	   var color_buttom = document.getElementsByName('color_buttom')[0];
	   color_buttom.id = 'color_buttom';
	   
	   var color_str_zagolovok = document.getElementsByName('color_str_zagolovok')[0];
	   color_str_zagolovok.id = 'color_str_zagolovok';
	   
	   var color_str_knopki = document.getElementsByName('color_str_knopki')[0];
	   color_str_knopki.id = 'color_str_knopki';
	   

	   

	   
	};
	
	setTimeout(() => {
/* 		$("#color_buttom").spectrum({
			color: "#ffffff"
		});
		$("#color_str_zagolovok").spectrum({
			color: "#0000a4"
		});
		$("#color_str_knopki").spectrum({
			color: "#0000a4"
		}); */
		
		

$(document).ready(function(){
	$("#color_buttom").spectrum({
		color: "#ffffff",
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxSelectionSize: 10,
		preferredFormat: "hex",
		localStorageKey: "spectrum.demo",
		move: function (color) {

		},
		show: function () {
			alert("show");
		},
		beforeShow: function () {
			alert("beforeShow");
		},
		hide: function () {
			alert("hide");
		},
		change: function() {
			
		   //var spinput = ;
		   alert(document.getElementsByClassName("sp-input")[0].value);
		},
		palette: [
			["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
			"rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
			["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
			"rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
			["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
			"rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
			"rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
			"rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
			"rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
			"rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
			"rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
			"rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
			"rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
			"rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
		]
	});
	
	$("#color_str_zagolovok").spectrum({
		color: "#0000a4",
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxSelectionSize: 10,
		preferredFormat: "hex",
		localStorageKey: "spectrum.demo",
		move: function (color) {

		},
		show: function () {

		},
		beforeShow: function () {

		},
		hide: function () {

		},
		change: function() {

		},
		palette: [
			["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
			"rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
			["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
			"rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
			["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
			"rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
			"rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
			"rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
			"rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
			"rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
			"rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
			"rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
			"rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
			"rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
		]
	});
	
	$("#color_str_knopki").spectrum({
		color: "#0000a4",
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxSelectionSize: 10,
		preferredFormat: "hex",
		localStorageKey: "spectrum.demo",
		move: function (color) {

		},
		show: function () {

		},
		beforeShow: function () {

		},
		hide: function () {

		},
		change: function() {

		},
		palette: [
			["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
			"rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
			["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
			"rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
			["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
			"rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
			"rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
			"rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
			"rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
			"rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
			"rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
			"rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
			"rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
			"rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
		]
	});
	
	
	
	
	
	
	
	
	
	
	
});
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}, 1000);
</script>




<?
$tabControl->End();

/*
 * Обрабатываем данные после отправки формы
 */
if ($request->isPost() && check_bitrix_sessid()) {

    foreach ($aTabs as $aTab) { // цикл по вкладкам
        foreach ($aTab['OPTIONS'] as $arOption) {
            if (!is_array($arOption)) { // если это название секции
                continue;
            }
            if ($arOption['note']) { // если это примечание
                continue;
            }
            if ($request['apply']) { // сохраняем введенные настройки
                $optionValue = $request->getPost($arOption[0]);
                if ($arOption[0] == 'switch_on') {
                    if ($optionValue == '') {
                        $optionValue = 'N';
                    }
                }
                if ($arOption[0] == 'jquery_on') {
                    if ($optionValue == '') {
                        $optionValue = 'N';
                    }
                }
                Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(',', $optionValue) : $optionValue);
            } elseif ($request['default']) { // устанавливаем по умолчанию
                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }

    LocalRedirect($APPLICATION->GetCurPage() . '?mid=' . $module_id .'&lang=' . LANGUAGE_ID);
}













?>


setTimeout(() => {
	
	$(document).ready(function(){
	  $('#color_buttom').ColorPicker({
		onSubmit: function(hsb, hex, rgb) {
			$('#color_buttom').val(hex);
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		}
	  })
	  .bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	  });
	});
	
	//alert("76678865");
	
	
	
}, 1000);



<?php
// module:authsms
// VNIMANIE! Udalyat' ili izmenyat' kommentarii b dannoy PHP-vstavke nel'zya, inache modul' authsms budet rabotat' nekorrektno, 
// dopuskaetsya udalit' polnost'yu dannuyu php-vstavku, modul' vstavit ego zanovo, dlya etogo neobhodimo zajti v ego nastrojki
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
global $USER;
if(!$USER->IsAuthorized()) {
	$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	$uri = new \Bitrix\Main\Web\Uri($request->getRequestUri());
	$uri->setPath('/sms/index.php'); 
	$uri->addParams(array('url_ref_type' => '/personal/order/index^^^php'));
	LocalRedirect($uri->getUri());
}
// module:authsms
?>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?><?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.ajax", 
	"order", 
	array(
		"ALLOW_NEW_PROFILE" => "Y",
		"SHOW_PAYMENT_SERVICES_NAMES" => "Y",
		"SHOW_STORES_IMAGES" => "N",
		"PATH_TO_BASKET" => SITE_DIR."basket.php",
		"PATH_TO_PERSONAL" => SITE_DIR."personal/?page=orders",
		"PATH_TO_PAYMENT" => SITE_DIR."payment.php",
		"PATH_TO_AUTH" => SITE_DIR."auth/",
		"PAY_FROM_ACCOUNT" => "N",
		"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
		"COUNT_DELIVERY_TAX" => "N",
		"ALLOW_AUTO_REGISTER" => "Y",
		"SEND_NEW_USER_NOTIFY" => "Y",
		"DELIVERY_NO_AJAX" => "Y",
		"DELIVERY_NO_SESSION" => "N",
		"TEMPLATE_LOCATION" => "popup",
		"DELIVERY_TO_PAYSYSTEM" => "d2p",
		"SET_TITLE" => "Y",
		"USE_PREPAYMENT" => "N",
		"DISABLE_BASKET_REDIRECT" => "N",
        "DISPLAY_IMG_HEIGHT" => "75",
		"PRODUCT_COLUMNS" => array(
			0 => "PREVIEW_PICTURE",
			1 => "PROPERTY_EMARKET_PREVIEW_CH",
		),
		"PROP_1" => array(
		)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
 <?php
// module:authsms
// VNIMANIE! Udalyat' ili izmenyat' kommentarii b dannoy PHP-vstavke nel'zya, inache modul' authsms budet rabotat' nekorrektno, 
// dopuskaetsya udalit' polnost'yu dannuyu php-vstavku, modul' vstavit ego zanovo, dlya etogo neobhodimo zajti v ego nastrojki
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
global $USER;
if(!$USER->IsAuthorized()) {
	$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	$uri = new \Bitrix\Main\Web\Uri($request->getRequestUri());
	$uri->setPath('/sms/index.php'); 
	$uri->addParams(array('url_ref_type' => '/auth/index^^^php'));
	LocalRedirect($uri->getUri());
}
// module:authsms
?>

<?
//define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

global $USER;
if(!$USER->IsAuthorized()) {
    LocalRedirect("/");
}

if (isset($_REQUEST["backurl"]) && strlen($_REQUEST["backurl"])>0) 
	LocalRedirect($backurl);

$APPLICATION->SetTitle("Авторизация");
?>
<p>Вы зарегистрированы и успешно авторизовались.</p>
 
<p>Используйте административную панель в верхней части экрана для быстрого доступа к функциям управления структурой и информационным наполнением сайта. Набор кнопок верхней панели отличается для различных разделов сайта. Так отдельные наборы действий предусмотрены для управления статическим содержимым страниц, динамическими публикациями (новостями, каталогом, фотогалереей) и т.п.</p>
 
<p><a href="<?=SITE_DIR?>">Вернуться на главную страницу</a></p>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

<?php
// module:authsms
// VNIMANIE! Udalyat' ili izmenyat' kommentarii b dannoy PHP-vstavke nel'zya, inache modul' authsms budet rabotat' nekorrektno, 
// dopuskaetsya udalit' polnost'yu dannuyu php-vstavku, modul' vstavit ego zanovo, dlya etogo neobhodimo zajti v ego nastrojki
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
global $USER;
if(!$USER->IsAuthorized()) {
	$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	$uri = new \Bitrix\Main\Web\Uri($request->getRequestUri());
	$uri->setPath('/sms/index.php'); 
	$uri->addParams(array('url_ref_type' => '/auth/registration^^^php'));
	LocalRedirect($uri->getUri());
}
// module:authsms
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("TITLE", "Универсальный интернет-магазин — OK-shop");
$APPLICATION->SetPageProperty("keywords", "Регистрация");
$APPLICATION->SetPageProperty("description", "Регистрация нового пользователя");
$APPLICATION->SetTitle("Регистрация");
?> <?$APPLICATION->IncludeComponent(
	"bitrix:main.register",
	"",
	Array(
		"USER_PROPERTY_NAME" => "",
		"SHOW_FIELDS" => array("NAME", "SECOND_NAME", "LAST_NAME", "PERSONAL_PHONE"),
		"REQUIRED_FIELDS" => array("NAME", "PERSONAL_PHONE"),
		"AUTH" => "Y",
		"USE_BACKURL" => "Y",
		"SUCCESS_PAGE" => "",
		"SET_TITLE" => "Y",
		"USER_PROPERTY" => array()
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>