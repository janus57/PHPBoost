<script type="text/javascript">
<!--
function refresh_img_{CAPTCHA_INSTANCE}()
{
	if ( typeof this.img_id == 'undefined' )
		this.img_id = 0;
	else
		this.img_id++;
	document.getElementById('verif_code_img{CAPTCHA_INSTANCE}').src = '{PATH_TO_ROOT}/PHPBoostCaptcha/ajax/display_image.php?width={CAPTCHA_WIDTH}&difficulty={CAPTCHA_DIFFICULTY}&height={CAPTCHA_HEIGHT}&font={CAPTCHA_FONT}&new=' + img_id;	

}
-->
</script>
<img src="{PATH_TO_ROOT}/PHPBoostCaptcha/ajax/display_image.php?width={CAPTCHA_WIDTH}&amp;height={CAPTCHA_HEIGHT}&amp;difficulty={CAPTCHA_DIFFICULTY}&amp;font={CAPTCHA_FONT}" id="verif_code_img{CAPTCHA_INSTANCE}" alt="" style="padding:2px;" />
<br />
<input size="30" type="text" class="text" name="{HTML_ID}" id="{HTML_ID}" />
<a href="javascript:refresh_img_{CAPTCHA_INSTANCE}()"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/refresh.png" alt="" class="valign_middle" /></a>