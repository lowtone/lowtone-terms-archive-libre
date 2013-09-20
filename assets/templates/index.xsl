<?xml version="1.0" encoding="UTF-8"?>
<!--
	@author Paul van der Meijs <code@paulvandermeijs.nl>
	@copyright Copyright (c) 2012, Paul van der Meijs
	@version 1.0
 -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<!-- Main > Check for taxonomy -->

	<xsl:template name="main">
		<xsl:param name="width">two-thirds</xsl:param>

		<section id="main" class="{$width} column">
			<xsl:call-template name="before_query" />
			<xsl:choose>
				<xsl:when test="taxonomy">
					<xsl:apply-templates select="taxonomy" mode="terms_archive" />
				</xsl:when>
				<xsl:otherwise>
					<xsl:apply-templates select="query" />
				</xsl:otherwise>
			</xsl:choose>
			<xsl:call-template name="after_query" />
		</section>
	</xsl:template>


	<!-- Terms archive -->

	<xsl:template match="taxonomy" mode="terms_archive">
		<div id="taxonomy-{query_var}" class="taxonomy terms_archive">
			<h1><xsl:value-of select="labels/name" /></h1>
			<xsl:apply-templates select="terms" mode="terms_archive" />
		</div>
	</xsl:template>


	<!-- Terms -->

	<xsl:template match="terms" mode="terms_archive">
		<ul class="terms">
			<xsl:apply-templates select="term" mode="terms_archive" />
		</ul>
	</xsl:template>


	<!-- Single term -->

	<xsl:template match="term" mode="terms_archive">
		<li id="term-{slug}" class="term term-taxonomy-{taxonomy}">
			<a href="{permalink}"><xsl:value-of select="name" disable-output-escaping="yes" /> <span class="count"><xsl:value-of select="count" /></span></a>
		</li>
	</xsl:template>
	
</xsl:stylesheet>