		<?$nnnumber_tempplate = COption::GetOptionString("clementin.authsms", "nnnumber_tempplate")?>

		<style>
			<?require_once($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/css_' . $nnnumber_tempplate . '/style.css')?>
			<?require_once($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/css_1/styleForModule.css')?>
		</style>
		
		<link rel="stylesheet" href="/sms/css/custom.css" />
		<link rel="stylesheet" type="text/css" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">

		<script>
			let url_ref = "<?=$url_ref?>";
			//console.log("url_ref:" + url_ref);
			let counter = -1;
			let counter_email = 0;

			function getRandomInt(min, max) {
				return Math.floor(Math.random() * (max - min)) + min;
			}
			
			function getCookie(name) {
				var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
				return matches ? decodeURIComponent(matches[1]) : undefined;
			}
			
			$(document).ready(function(){
				$('#tab__title a').on("click", function(){
					$('#tab__title a').removeClass("tab__active");
					$(this).addClass("tab__active");
					var href = $(this).attr('href');
					$('.tab__content').removeClass('tab__active');
					$(href).addClass('tab__active');
				})
			});
			
			// получение
			let module_clementin_authsms_user_id = getCookie("module_clementin_authsms_user_id");
			if(!module_clementin_authsms_user_id) {
				module_clementin_authsms_user_id = getRandomInt(1111111111, 9999999999);
				document.cookie = "module_clementin_authsms_user_id=" + module_clementin_authsms_user_id;
			}
			if(module_clementin_authsms_user_id) {
				$.post("/include/clementin.authsms/handler_ajax_code.php", {
					module_clementin_authsms_user_id:module_clementin_authsms_user_id,
					strlen_generir_code:"<?= $strlen_generir_code ?>"
				}, function(data) {

				});
			}
		</script>

		<?require_once($_SERVER["DOCUMENT_ROOT"] . '/include/clementin.authsms/content/form_' .$nnnumber_tempplate. '.php')?>
		
		<script>
			let phone = document.getElementById("input_tel");
			let email = document.getElementById("input_email");
			let btnClearPhone = document.querySelector(".sms-form__input-container__clear");
			let inputsForCodeTel = document.querySelectorAll(".sms-form__input_change");
			let inputsForCodeEmail = document.querySelectorAll(".email-form__input_change");
			//console.log(inputsForCodeTel);
			//console.log(inputsForCodeEmail);

			for(let i = 0; i < inputsForCodeTel.length; i++) {
				inputsForCodeTel[i].addEventListener('input', () => {
					if(inputsForCodeTel[i].value != "") {
						for(let j = 0; j < inputsForCodeTel.length; j++) {
							if(inputsForCodeTel[j].value == "") {
								inputsForCodeTel[j].focus();
								break;
							}
						}
					}
				})
			}
			
			for(let i = 0; i < inputsForCodeEmail.length; i++) {
				inputsForCodeEmail[i].addEventListener('input', () => {
					if(inputsForCodeEmail[i].value != "") {
						for(let j = 0; j < inputsForCodeEmail.length; j++) {
							if(inputsForCodeEmail[j].value == "") {
								inputsForCodeEmail[j].focus();
								break;
							}
						}
					}
				})
			}

			<?if($bez_knopki_on == 'Y'):?>
				document.getElementById('form__button').style.display = "none";
				
				email.addEventListener('input', () => {
					var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;	// validate e-mail
					if(reg.test(email.value) === true) {
						//console.log("96");
						FormAct();
					}
				})
				inputsForCodeTel[inputsForCodeTel.length - 1].addEventListener('input', () => {
					if(inputsForCodeTel[inputsForCodeTel.length - 1].value != "") {
						console.log("114");
						FormAct();
					}
				})
				inputsForCodeEmail[inputsForCodeEmail.length - 1].addEventListener('input', () => {
					if(	[inputsForCodeEmail.length - 1].value != "") {
						//console.log("108");
						FormAct();
					}
				})
				phone.addEventListener('input', () => {
					nom_tel_paste = phone.value.replace(/\D+/g, '');
					//alert(nom_tel_paste);
					if(nom_tel_paste.length == 10 && nom_tel_paste.slice(0, 1) != "7") {
						phone.value = "7" + nom_tel_paste;
						//console.log("116");
						FormAct();
					}
					if(nom_tel_paste.length == 11 && nom_tel_paste.slice(0, 1) == "7") {
						//console.log("121");
						FormAct();
					}
					if(nom_tel_paste.length == 11 && nom_tel_paste.slice(0, 1) == "8") {
						phone.value = "7" + nom_tel_paste.substr(1);
						//console.log("127");
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
			document.getElementById('otpr_code_povtorno_1').style.display = "none";
			document.getElementById('otpr_code_povtorno_2').style.display = "none";
			document.getElementById('tel_err').style.display = "none";
			document.getElementById("input_email").value = "";
			document.getElementById("input_tel").value = "";
		</script>

		<script>
			function FormAct() {
				//console.log("======================================================================================================:");
				var nom_tel = document.getElementById('input_tel').value;
				nom_tel = nom_tel.replace(/\D+/g, '');
				var code_1 = document.getElementById('sms-form-code1').value + document.getElementById('sms-form-code2').value + document.getElementById('sms-form-code3').value + document.getElementById('sms-form-code4').value;
				var code_2 = document.getElementById('email-form-code1').value + document.getElementById('email-form-code2').value + document.getElementById('email-form-code3').value + document.getElementById('email-form-code4').value;
				var code = "";
				if(code_1.length > 1) code = code_1;
				if(code_2.length > 1) code = code_2;
				code = code.replace(/\D+/g, '');
				
				// validate e-mail
				var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
				var address = email.value;
				if(reg.test(address) == false) {
					document.getElementById('email_err').style.display = "block";
					//document.getElementById('otpr_code_povtorno_1').style.display = "none";
					document.getElementById('input_email').classList.add("sms-form__input-incorrect");
				} else if(counter_email == 0) {
					var request = new XMLHttpRequest();
					request.open("POST", "/include/clementin.authsms/handler_ajax_sms.php", true);
					//console.log("207");
					request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					
					request.addEventListener("readystatechange", () => {
						if(request.readyState === 4 && request.status === 200) {
							<?if($nnnumber_tempplate == 1):?>
								document.getElementById("input_email").style.display = "none";
							<?else:?>
								document.getElementById('input_email').classList.add("sms-form__input-disable");
								document.getElementById('tab__title_invers_email').style.display = "flex";
							<?endif?>
							document.getElementById('input_email').classList.remove("sms-form__input-incorrect");
							document.getElementById('tab__title').style.display = "none";
							document.getElementById('code_email').style.display = "block";
							document.getElementById('email_err').style.display = "none";
							
							<?if($nnnumber_tempplate == 1):?>
								document.getElementById('sms-form__codeForNumber').style.display = "block";
							<?endif?>
							
							
							document.getElementById('nadp_button').innerHTML = "<?=$MESS['clementin.authsms_OPTIONS_voiti']?>";
							inputsForCodeEmail[0].focus();
							counter_email ++;
							//console.log("counter_email=" + counter_email);

							if(request.responseText.indexOf('error') != -1 && document.getElementById('ajax_err')) {
								document.getElementById('ajax_err').style.opacity = "1";
							}
							
							<?if($nnnumber_tempplate == 1):?>
								document.getElementById('sms-form__codeForNumber').innerHTML = '<?=$MESS['clementin.authsms_OPTIONS_vvkkbpnaadres']?> <span style="font-weight: 600;">' + address + "</span>:";
							<?endif?>
						}
					});
						request.send("email=" + address + "&msg=" + getCookie("module_clementin_authsms_user_id"));

					// timer
					function startCount() {
						document.getElementById('otpr_code_povtorno_1').style.display = "block";
						document.getElementById('otpr_code_povtorno_2').style.display = "block";
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
							//console.log("click");
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
				
				if(code.length == <?= $strlen_generir_code ?> && reg.test(address) == true && counter_email == 1  && address) {
					$.post("/include/clementin.authsms/handler_ajax_code_pr.php", {
						"module_clementin_authsms_user_id": module_clementin_authsms_user_id,
						"code": code,
					}, function(data) {
						if(String(data) == "Y") {
							$.post("/include/clementin.authsms/handler_ajax_user.php", {
								"email": address
							}, function(data) {
								data = String(data);
								if(data.length > 0) {
									let old_new = data.slice(-3);
									data = data.slice(0, data.length - 3);
									//console.log("perehod===287====>/include/clementin.authsms/handler_ajax_autor.php?url_ref=" + url_ref + "&id=" + data + "&old_new=" + old_new);
									window.location.href = "/include/clementin.authsms/handler_ajax_autor.php?url_ref=" + url_ref + "&id=" + data + "&old_new=" + old_new;
								} else {
									document.getElementById('zvonok').style.display = "block";
									document.getElementById('ajax_err_zvonok').style.display = "block";
									document.getElementById('ajax_err_zvonok').style.opacity = "1";
									document.getElementById('ajax_err_zvonok').innerHTML = data;
								}
							});
						} else {
							document.getElementById('code_err_email').style.display = "block";
							document.getElementById('code_err_email').innerHTML = "<?=$MESS['clementin.authsms_OPTIONS_50']?>";
							document.getElementById('sms-form-code1').classList.add("sms-form__input-incorrect");
							document.getElementById('sms-form-code2').classList.add("sms-form__input-incorrect");
							document.getElementById('sms-form-code3').classList.add("sms-form__input-incorrect");
							document.getElementById('sms-form-code4').classList.add("sms-form__input-incorrect");
							document.getElementById('email-form-code1').classList.add("sms-form__input-incorrect");
							document.getElementById('email-form-code2').classList.add("sms-form__input-incorrect");
							document.getElementById('email-form-code3').classList.add("sms-form__input-incorrect");
							document.getElementById('email-form-code4').classList.add("sms-form__input-incorrect");
						}
					});
				}

				<?if($bez_knopki_on != 'Y'):?>
					if(nom_tel.length !== 11) {
						document.getElementById('tel_err').style.display = "block";
						document.getElementById('input_tel').classList.add("sms-form__input-incorrect");
						//document.getElementById('otpr_code_povtorno_1').style.display = "none";
					}
				<?endif?>

				<? if($type_mess == 'type_mess_sms' || ($type_mess == 'type_mess_zvonok' && $provider != 'smsru')) : ?>
					if(nom_tel.length == 11 && code.length == 0) { //posle vvoda tel
						var request = new XMLHttpRequest();
						request.open("POST", "/include/clementin.authsms/handler_ajax_sms.php", true);
						//console.log("325");
						request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						request.addEventListener("readystatechange", () => {
							if (request.readyState === 4 && request.status === 200) {
								//alert("smssend--->" + request.responseText);
								<?if($nnnumber_tempplate == 1):?>
									document.getElementById("input_tel").style.display = "none";
								<?else:?>
									document.getElementById('input_tel').classList.add("sms-form__input-disable");
									document.getElementById('tab__title_invers_tel').style.display = "flex";
								<?endif?>
								document.getElementById('tab__title').style.display = "none";
								document.getElementById('code_sms').style.display = "block";
								document.getElementById('tel_err').style.display = "none";
								inputsForCodeTel[0].focus();
								<?if($nnnumber_tempplate == 1):?>
									document.getElementById('sms-form__codeForNumber').style.display = "block";
								<?endif?>
								let arrPhone = nom_tel.split("");
								arrPhone.unshift("+");
								arrPhone.splice(2, 0, " ");
								arrPhone.splice(3, 0, "(");
								arrPhone.splice(7, 0, ")");
								arrPhone.splice(8, 0, " ");
								arrPhone.splice(12, 0, " ");
								let stringPhone = arrPhone.join("");
								
								<?if($nnnumber_tempplate == 1):?>
									<? if ($type_mess == 'type_mess_zvonok' && $provider != 'smsru') : ?>
										document.getElementById('sms-form__codeForNumber').innerHTML = "<?=$MESS['clementin.authsms_OPTIONS_10']?> " + stringPhone + ". <?=$MESS['clementin.authsms_OPTIONS_20']?>:";
									<? else : ?>
										document.getElementById('sms-form__codeForNumber').innerHTML = "<?=$MESS['clementin.authsms_OPTIONS_30']?>: <span>" + stringPhone + ":</span>";
									<? endif ?>
								<? endif ?>
								
								document.getElementById('tel_err').style.display = "none";
								document.getElementById('nadp_button').innerHTML = "<?=$MESS['clementin.authsms_OPTIONS_voiti']?>";

								<? if ($type_mess == 'type_mess_zvonok' || $provider != 'smsru') : ?>
									if (request.responseText.indexOf('OK CODE:') != -1) {
										document.cookie = "code_4_nomera_tel=" + request.responseText.replace("OK CODE:", "");
									}
									if (request.responseText.indexOf('error') != -1 && document.getElementById('ajax_err')) {
										document.getElementById('ajax_err').style.opacity = "1";
									}
									
									if(code.length == <?= $strlen_generir_code ?>) {
										$.post("/include/clementin.authsms/handler_ajax_user.php", {
											"nom_tel": nom_tel
										}, function(data) {
											//console.log("data:" + data);
											if(data.length > 0) {
												let old_new = data.slice(-3);
												data = data.slice(0, data.length - 3);
												//console.log("perehod===364====>/include/clementin.authsms/handler_ajax_autor.php?url_ref=" + url_ref + "&id=" + data + "&old_new=" + old_new);
												window.location.href = "/include/clementin.authsms/handler_ajax_autor.php?url_ref=" + url_ref + "&id=" + data + "&old_new=" + old_new;
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
							var str_post = "nom_tel=" + nom_tel + "&msg=" + getCookie("module_clementin_authsms_user_id");
						<? endif ?>
						<? if ($type_mess == 'type_mess_zvonok' && $provider != 'smsru') : ?>
							var str_post = "nom_tel=" + nom_tel;
						<? endif ?>
						request.send(str_post);

						// timer
						function startCount() {
							document.getElementById('otpr_code_povtorno_1').style.display = "block";
							document.getElementById('otpr_code_povtorno_2').style.display = "block";
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
								//console.log("click");
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
					document.getElementById('code_err').style.display = "block";
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

				<? if($type_mess == 'type_mess_sms') : ?>
					if(code.length == <?= $strlen_generir_code ?>  && !address) { // esli code.length verniy
						$.post("/include/clementin.authsms/handler_ajax_code_pr.php", {
							"module_clementin_authsms_user_id": module_clementin_authsms_user_id,
							"code": code,
						}, function(data) {
							if(String(data) == "Y") {
								$.post("/include/clementin.authsms/handler_ajax_user.php", {
									"nom_tel": nom_tel
								}, function(data) {
									data = String(data);
									if(data.length > 0) {
										let old_new = data.slice(-3);
										data = data.slice(0, data.length - 3);
										//console.log("perehod===450====>/include/clementin.authsms/handler_ajax_autor.php?url_ref=" + url_ref + "&id=" + data + "&old_new=" + old_new);
										window.location.href = "/include/clementin.authsms/handler_ajax_autor.php?url_ref=" + url_ref + "&id=" + data + "&old_new=" + old_new;
									} else {
										document.getElementById('zvonok').style.display = "block";
										document.getElementById('ajax_err_zvonok').style.display = "block";
										document.getElementById('ajax_err_zvonok').style.opacity = "1";
										document.getElementById('ajax_err_zvonok').innerHTML = data;
									}
								});
							} else {
								document.getElementById('code_err').style.display = "block";
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
						});
						
					}
				<? endif ?>

				<? if ($type_mess == 'type_mess_zvonok' && $provider == 'smsru') : ?>
					counter++;
					if(nom_tel.length == 11 && counter == 0) { //первый запрос
						console.log("472");
						$.post("/include/clementin.authsms/handler_ajax_sms.php", {
							"nom_tel": nom_tel,
							"zapros1": "Y"
						}, function(data1) {
							//console.log("data1:" + data1);
							data1 = JSON.parse(data1);
							if (data1["status"] == "OK") {
								$("#sms-form__codeForNumber").html("<?=$MESS['clementin.authsms_OPTIONS_60']?> +" + nom_tel + " <?=$MESS['clementin.authsms_OPTIONS_70']?> " + data1["call_phone_html"] + ", <?=$MESS['clementin.authsms_OPTIONS_80']?>");
								document.cookie = "check_id=" + data1["check_id"];
							} else {
								document.getElementById('ajax_err_zvonok').style.opacity = "1";
							}
						});

					}

					if(counter > 0) { // второй запрос
						console.log("490");
						$.post("/include/clementin.authsms/handler_ajax_sms.php", {
							"nom_tel": nom_tel,
							"check_id": getCookie('check_id'),
							"zapros2": "Y"
						}, function(data2) {
							//console.log("data2:" + data2);
							data2 = JSON.parse(data2);
							if (data2["status"] == "OK" && data2["check_status"] == 401) {
								$.post("/include/clementin.authsms/handler_ajax_user.php", {
									"nom_tel": nom_tel
								}, function(data3) {
									//console.log("data3:" + data3);
									if(data3.length > 0) {
										let old_new = data3.slice(-3);
										data = data3.slice(0, data.length - 3);
										//console.log("perehod===513====>/include/clementin.authsms/handler_ajax_autor.php?url_ref=" + url_ref + "&id=" + data + "&old_new=" + old_new);
										window.location.href = "/include/clementin.authsms/handler_ajax_autor.php?url_ref=" + url_ref + "&id=" + data + "&old_new=" + old_new;
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