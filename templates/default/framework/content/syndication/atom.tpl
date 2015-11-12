<?xml version="1.0" encoding="windows-1252" ?>
<!-- ATOM generated by PHPBoost on {DATE_RFC3339} -->
<feed xmlns="http://www.w3.org/2005/Atom">
	<title>{TITLE}</title>
	<subtitle>{DESC}</subtitle>
	<link rel="self" type="application/atom+xml" href="{U_LINK}"/>
	<updated>{DATE_RFC3339}</updated>
	<author>
		<name>PHPBoost</name>
		<email>feed@phpboost.com</email>
	</author>
	<id>{U_LINK}</id>
	
	# START item #
	<entry>
		<title>{item.TITLE}</title>
		<link href="{item.U_LINK}"/>
		<id>{item.U_GUID}</id>
		<updated>{item.DATE_RFC3339}</updated>
		<content type="html">{item.DESC}</content>
		# IF item.C_ENCLOSURE #
			<enclosure rel="enclosure" url="{item.ENCLOSURE_URL}" length="{item.ENCLOSURE_LENGHT}" type="{item.ENCLOSURE_TYPE}" />
		# ENDIF #
	</entry>
	# END item #
	
</feed>

