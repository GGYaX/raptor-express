<?php
/**
* @package Joomla.Administrator
* @subpackage com_helloworld
*
* @copyright Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/

// No direct access to this file
defined('_JEXEC') or die();
?>
<div class="container">
	<div class="registration">
		<form action="<?php echo JURI::current() ?>" method="post" accept-charset="utf-8" class="form-validate form-horizontal">
			<fieldset>
				<div class="form-box">
					<legend>新建地址</legend>
					<div class="row">
						<div class="col-sm-6">
							<div class="control-label">
								<span class="spacer"><span class="before"></span><span class="text"><label id="jform_spacer-lbl" class=""><strong class="red">*</strong> 必填字段</label></span><span class="after"></span></span>
							</div>
								<div class=""></div>
						</div>
						<div class="col-sm-6">
							<div class="control-label">
								<label id="jform_name-lbl" for="jform_name" class="hasTooltip required" title="" data-original-title="<strong>姓名：</strong><br />输入收件人/发件人全名" aria-invalid="true">姓名：<span class="star">&nbsp;*</span></label>
							</div>
							<div class="">
								<input type="text" name="jform[name]" id="jform_name" value="" class="required" size="30" required="required" aria-required="true" aria-invalid="true">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="control-label">
								<label id="jform_street-lbl" for="jform_street" class="hasTooltip required" title="" data-original-title="<strong>街道名字：</strong><br />输入街道名字" aria-invalid="true">街道名字：<span class="star">&nbsp;*</span></label>
							</div>
							<div class="">
								<input type="text" name="jform[street]" id="jform_street" value="" class="required" size="30" required="required" aria-required="true" aria-invalid="true">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="control-label">
								<label id="jform_zip_code-lbl" for="jform_zip_code" class="hasTooltip required" title="" data-original-title="<strong>邮编：</strong><br />输入邮编" aria-invalid="true">邮编：<span class="star">&nbsp;*</span></label>
							</div>
							<div class="">
								<input type="text" name="jform[zip_code]" id="jform_zip_code" value="" class="required" size="30" required="required" aria-required="true" aria-invalid="true">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="control-label">
								<label id="jform_country-lbl" for="jform_country" class="hasTooltip required" title="" data-original-title="<strong>国家：</strong><br />输入国家" aria-invalid="true">国家：<span class="star">&nbsp;*</span></label>
							</div>
							<div class="">
								<select name="jform[country]" id="jform_country">
									<script>
									var countryCode = {"AF":"Afghanistan","AL":"Albania","DZ":"Algeria","AS":"American Samoa","AD":"Andorra","AG":"Angola","AI":"Anguilla","AG":"Antigua Barbuda","AR":"Argentina","AA":"Armenia","AW":"Aruba","AU":"Australia","AT":"Austria","AZ":"Azerbaijan","BS":"Bahamas","BH":"Bahrain","BD":"Bangladesh","BB":"Barbados","BY":"Belarus","BE":"Belgium","BZ":"Belize","BJ":"Benin","BM":"Bermuda","BT":"Bhutan","BO":"Bolivia","BL":"Bonaire","BA":"Bosnia Herzegovina","BW":"Botswana","BR":"Brazil","BC":"British Indian Ocean Ter","BN":"Brunei","BG":"Bulgaria","BF":"Burkina Faso","BI":"Burundi","KH":"Cambodia","CM":"Cameroon","CA":"Canada","IC":"Canary Islands","CV":"Cape Verde","KY":"Cayman Islands","CF":"Central African Republic","TD":"Chad","CD":"Channel Islands","CL":"Chile","CN":"China","CI":"Christmas Island","CS":"Cocos Island","CO":"Colombia","CC":"Comoros","CG":"Congo","CK":"Cook Islands","CR":"Costa Rica","CT":"Cote D'Ivoire","HR":"Croatia","CU":"Cuba","CB":"Curacao","CY":"Cyprus","CZ":"Czech Republic","DK":"Denmark","DJ":"Djibouti","DM":"Dominica","DO":"Dominican Republic","TM":"East Timor","EC":"Ecuador","EG":"Egypt","SV":"El Salvador","GQ":"Equatorial Guinea","ER":"Eritrea","EE":"Estonia","ET":"Ethiopia","FA":"Falkland Islands","FO":"Faroe Islands","FJ":"Fiji","FI":"Finland","FR":"France","GF":"French Guiana","PF":"French Polynesia","FS":"French Southern Ter","GA":"Gabon","GM":"Gambia","GE":"Georgia","DE":"Germany","GH":"Ghana","GI":"Gibraltar","GB":"Great Britain","GR":"Greece","GL":"Greenland","GD":"Grenada","GP":"Guadeloupe","GU":"Guam","GT":"Guatemala","GN":"Guinea","GY":"Guyana","HT":"Haiti","HW":"Hawaii","HN":"Honduras","HK":"Hong Kong","HU":"Hungary","IS":"Iceland","IN":"India","ID":"Indonesia","IA":"Iran","IQ":"Iraq","IR":"Ireland","IM":"Isle of Man","IL":"Israel","IT":"Italy","JM":"Jamaica","JP":"Japan","JO":"Jordan","KZ":"Kazakhstan","KE":"Kenya","KI":"Kiribati","NK":"Korea North","KS":"Korea South","KW":"Kuwait","KG":"Kyrgyzstan","LA":"Laos","LV":"Latvia","LB":"Lebanon","LS":"Lesotho","LR":"Liberia","LY":"Libya","LI":"Liechtenstein","LT":"Lithuania","LU":"Luxembourg","MO":"Macau","MK":"Macedonia","MG":"Madagascar","MY":"Malaysia","MW":"Malawi","MV":"Maldives","ML":"Mali","MT":"Malta","MH":"Marshall Islands","MQ":"Martinique","MR":"Mauritania","MU":"Mauritius","ME":"Mayotte","MX":"Mexico","MI":"Midway Islands","MD":"Moldova","MC":"Monaco","MN":"Mongolia","MS":"Montserrat","MA":"Morocco","MZ":"Mozambique","MM":"Myanmar","NA":"Nambia","NU":"Nauru","NP":"Nepal","AN":"Netherland Antilles","NL":"Netherlands (Holland, Europe)","NV":"Nevis","NC":"New Caledonia","NZ":"New Zealand","NI":"Nicaragua","NE":"Niger","NG":"Nigeria","NW":"Niue","NF":"Norfolk Island","NO":"Norway","OM":"Oman","PK":"Pakistan","PW":"Palau Island","PS":"Palestine","PA":"Panama","PG":"Papua New Guinea","PY":"Paraguay","PE":"Peru","PH":"Philippines","PO":"Pitcairn Island","PL":"Poland","PT":"Portugal","PR":"Puerto Rico","QA":"Qatar","ME":"Republic of Montenegro","RS":"Republic of Serbia","RE":"Reunion","RO":"Romania","RU":"Russia","RW":"Rwanda","NT":"St Barthelemy","EU":"St Eustatius","HE":"St Helena","KN":"St Kitts-Nevis","LC":"St Lucia","MB":"St Maarten","PM":"St Pierre Miquelon","VC":"St Vincent Grenadines","SP":"Saipan","SO":"Samoa","AS":"Samoa American","SM":"San Marino","ST":"Sao Tome Principe","SA":"Saudi Arabia","SN":"Senegal","RS":"Serbia","SC":"Seychelles","SL":"Sierra Leone","SG":"Singapore","SK":"Slovakia","SI":"Slovenia","SB":"Solomon Islands","OI":"Somalia","ZA":"South Africa","ES":"Spain","LK":"Sri Lanka","SD":"Sudan","SR":"Suriname","SZ":"Swaziland","SE":"Sweden","CH":"Switzerland","SY":"Syria","TA":"Tahiti","TW":"Taiwan","TJ":"Tajikistan","TZ":"Tanzania","TH":"Thailand","TG":"Togo","TK":"Tokelau","TO":"Tonga","TT":"Trinidad Tobago","TN":"Tunisia","TR":"Turkey","TU":"Turkmenistan","TC":"Turks Caicos Is","TV":"Tuvalu","UG":"Uganda","UA":"Ukraine","AE":"United Arab Emirates","GB":"United Kingdom","US":"United States of America","UY":"Uruguay","UZ":"Uzbekistan","VU":"Vanuatu","VS":"Vatican City State","VE":"Venezuela","VN":"Vietnam","VB":"Virgin Islands (Brit)","VA":"Virgin Islands (USA)","WK":"Wake Island","WF":"Wallis Futana","YE":"Yemen","ZR":"Zaire","ZM":"Zambia","ZW":"Zimbabwe"};
									var toInsert = '';
									var optionBegin = '<option value=';
									var optionEnd = '</option>';
									for(var code in countryCode) {
										toInsert += (optionBegin + '"' + code + '"' + (code === 'CN' ? 'selected="selected"' : '') + '>' + countryCode[code] + optionEnd);
									}
									document.getElementById("jform_country").innerHTML = toInsert;
									</script>
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="control-label">
								<label id="jform_phone-lbl" for="jform_phone" class="hasTooltip required" title="" data-original-title="<strong>联系电话：</strong><br />输入联系电话" aria-invalid="true">联系电话：<span class="star">&nbsp;*</span></label>
							</div>
							<div class="">
								<input type="text" name="jform[phone]" id="jform_phone" value="" class="required" size="30" required="required" aria-required="true" aria-invalid="true">
							</div>
						</div>
					</div>
				</div>
			</fieldset>
			<div class="form-actions">
				<button type="submit" class="btn btn-rounded btn-primary btn-lg smooth-scroll validate">添加地址</button>
			</div>
		</form>
	</div>
</div>