<table>
	<thead>
		<tr> 
			<th>
			</th>
			<th>
				{@streams.name}
			</th>
			<th>
				{@streams.description}
			</th>
			<th>
				{@newsletter.archives}
			</th>
			<th>
				{@newsletter.subscribers}
			</th>
		</tr>
	</thead>
	# IF C_STREAMS #
		<tfoot>
			<tr>
				<th colspan="5">
					{PAGINATION}
				</th>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td colspan="2">
					<a href="{LINK_SUBSCRIBE}">{@newsletter.subscribe_newsletters}</a>
				</td>
				<td class="no-separator"></td>
				<td colspan="2" class="no-separator">
					<a href="{LINK_UNSUBSCRIBE}">{@newsletter.unsubscribe_newsletters}</a>
				</td>
			</tr>
			# START streams_list #
			<tr>
				<td> 
					<img src="{streams_list.PICTURE}"></img>
				</td>
				<td>
					{streams_list.NAME}
				</td>
				<td>
					{streams_list.DESCRIPTION}
				</td>
				<td>
					{streams_list.VIEW_ARCHIVES}
				</td>
				<td>
					{streams_list.VIEW_SUBSCRIBERS}
				</td>
			</tr>
			# END streams_list #
		</tbody>
	# ELSE #
		<tbody>
			<tr>
				<td colspan="5">
					<span class="text-strong">{@newsletter.no_newsletters}</span>
				</td>
			</tr>
		</tbody>
	# ENDIF #
</table>