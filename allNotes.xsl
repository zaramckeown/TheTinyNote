<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
  <html>
  <body>
  <h2 id='allNoteDisplay'>All Notes</h2>
    <table id="allNoteDisplay">
      <tr class="heading">
        <th>Title</th>
        <th>Sender</th>
        <th>Recipient</th>
        <th>Date</th>
        <th>Message</th>
        <th>Url</th>
		<th>Status</th>
      </tr>
      <xsl:for-each select="postItNotes/notes/note">
            <tr>
              <td class="center"><xsl:value-of select="title"/></td>
              <td class="center"><xsl:value-of select="sender"/></td>
              <td class="center"><xsl:value-of select="recipients"/></td>
              <td class="center"><xsl:value-of select="date"/></td>
              <td class="center"><xsl:value-of select="message"/></td>
              <td class="center"><a href="{url}"><xsl:value-of select="url"/></a> </td>
              <td class="center"><xsl:value-of select="messageStatus"/></td>
            </tr>
      </xsl:for-each>
    </table>
  </body>
  </html>
</xsl:template>
</xsl:stylesheet>