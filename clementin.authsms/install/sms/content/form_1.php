		<section class="sms-form">
			<form class="sms-form__container" action="javascript:void(null)" onsubmit="FormAct()">
				<a href="<?= $url_ref ?>" class="sms-form__container__close">
					<i class="fa fa-times" aria-hidden="true"></i>
				</a>

				<h2 class="sms-form__title"><?= $str_zagolovok ?></h2>
				<p class="" id="sms-form__codeForNumber"></p>
				<div class="tabs sms-form__inner-container">
					<div class="tab__title" id="tab__title">
						<div>
							<a href="" class="tab__active"	onClick='
																document.getElementById("tab__email").style.display = "none";
																document.getElementById("tab__tel").style.display = "block";
																document.getElementById("input_tel").focus();
																return false;
																'><?=$MESS['clementin.authsms_OPTIONS_telefon']?>
							</a>
						</div>
						<div>
							<a href="" 					onClick='
																document.getElementById("tab__tel").style.display = "none";
																document.getElementById("tab__email").style.display = "block";
																document.getElementById("input_email").focus();
																return false;
																'>E-mail
							</a>
						</div>&nbsp;&nbsp;
						<?if($std_reg_on == 'Y'):?>
							<div>
								<a href="<?=$PATH_TO_REG_STD?>">
									<?=$str_npcrbx?>
								</a>
							</div>
						<?endif?>
					</div>

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
							<span id="otpr_code_povtorno_1">
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
							<span id="otpr_code_povtorno_2"><?=$MESS['clementin.authsms_OPTIONS_otobrazit_kod_eche_raz']?></span>
							<span class="sms-form__new-code_dynamic" id="sms-form__new-code_dynamic-2"></span> <?=$MESS['clementin.authsms_OPTIONS_cecund']?>
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