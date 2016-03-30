<div class="searchRevPaginTopDiv">
	<div class="flt-l">
		<table class="paginator">
			<tbody>
				<tr valign="top">
					<td><span class="paginatorResultTitle" >
							<?php echo $X_langArray['SEARCH_REVIEW_RESULT_PAGIN_RESULT'] ?>&nbsp;<b><?php echo $dim ?></b>
					</span></td>
					<?php  	
					if ( $dim > 1 ) {
						if ( $current > 0 ) { ?>		
							<td><a href="/viantes/pub/pages/review/searchReviewResult.php?searchRevResCur=<?php echo ($current-1);?>" >
								<span class="paginatorTDSpan"> 
									<?php echo $X_langArray['SEARCH_REVIEW_RESULT_PAGIN_BACK'] ?>
								</span>
							</a></td>
						<?php
						}
						//numero di risultati - parametro di configurazione
						$srchResNum = Conf::getInstance()->get('searchResultNum'); 
						$limit = $srchResNum / 2; 
						$min = 0; $max = $dim < $srchResNum ? $dim : $srchResNum;
						if ($current > $limit && $current + $limit < $dim) {
							$min = $current - $limit; 
							$max = $current + $limit;
						} else if ($current > $limit ){
							$min = $dim - $srchResNum > 0 ? $dim - $srchResNum : 0;
							$max = $dim;
						}
						//exit;
						for ( $i = $min; $i < $max; $i++ ) { ?>
							<td>
								<?php if ( $current != $i ) { ?>
									<a href="/viantes/pub/pages/review/searchReviewResult.php?searchRevResCur=<?php echo ($i);?>" >
										<span class="paginatorTDSpan"><?php echo ($i+1); ?></span></a>
								<?php } else { ?>
									<span class="paginatorTDSpan"><?php echo ($i+1); ?></span></a>
								<?php } ?>
							</td>
						<?php 
						}
						if ($current + 1 < $dim) { ?>
							<td><a style="text-align:left" href="/viantes/pub/pages/review/searchReviewResult.php?searchRevResCur=<?php echo ($current+1)?>" >
									<span class="paginatorTDSpan"> 
										<?php echo $X_langArray['SEARCH_REVIEW_RESULT_PAGIN_NEXT'] ?>
									</span>
							</a></td>
					<?php }  
					}
					?>
				</tr>
			</tbody>
		</table>
	</div>
	
	<?php  if ( $dim > 1 ) { ?>
		<div class="flt-r">
			<div>
				<?php 
				echo $X_langArray['SSO_ORDER_BY'];
				$criteria = isset($_SESSION["SEARCH_REVIEW_SEARCH_CRITERIA"]) ? $_SESSION["SEARCH_REVIEW_SEARCH_CRITERIA"]
																			  : $_SESSION["SEARCH_REV_HEADER_SEARCH_CRIT"];
				$ordTyp = $criteria['orderType']; ?>
				<select style="width:180px" onchange="$('#orderType').val(this.value); $('#searchRevAdvFrmId').submit();">
					<option value="0" <?php if ($ordTyp==0) echo "selected" ?> >
						<?php echo $X_langArray['SSO_ORDER_DATA']?>
					</option>
					<option value="1" <?php if ($ordTyp==1) echo "selected" ?> >
						<?php echo $X_langArray['SSO_ORDER_DATA_DESC']?>
					</option>
					<option value="2" <?php if ($ordTyp==2) echo "selected" ?> >
						<?php echo $X_langArray['SSO_ORDER_VOTE']?>
					</option>
					<option value="3" <?php if ($ordTyp==3) echo "selected" ?> >
						<?php echo $X_langArray['SSO_ORDER_VOTE_DESC']?>
					</option>
					<option value="4" <?php if ($ordTyp==4) echo "selected" ?> >
						<?php echo $X_langArray['SSO_ORDER_STAR']?>
					</option>
					<option value="5" <?php if ($ordTyp==5) echo "selected" ?> >
						<?php echo $X_langArray['SSO_ORDER_STAR_DESC']?>
					</option>
				</select>
			</div>
		</div>
		<?php  
		//Se e' settato SEARCH_REVIEW_SEARCH_CRITERIA => arrivo dalla searchReviewFromAdv
		if (isset($_SESSION["SEARCH_REVIEW_SEARCH_CRITERIA"])) { ?>
			<form id="searchRevAdvFrmId" action="/viantes/pvt/pages/review/search/searchReviewFromAdv.php" method="post">
		<?php  
		//Se e' settato SEARCH_REV_HEADER_SEARCH_CRIT => arrivo dalla searchReviewFromHeader
		} else if (isset($_SESSION["SEARCH_REV_HEADER_SEARCH_CRIT"])) { ?>
			<form id="searchRevAdvFrmId" action="/viantes/pvt/pages/review/search/searchReviewFromHeader.php" method="post">
		<?php } ?>
		
			<!-- n.b. come parola chiave non metto quella dei criteri, perche', in caso di risultati multipli, ripresenterei ancora 
					  la pagina searchReviewMultiResult... devo emttere il nome del sito/citta/nazione ottenura DOPO la ricerca -->
			<input type="hidden" name="placeId"    value="<?php echo ($criteria['type']==1 ? X_code( $reviewDO->getSiteId() ) :
																			($criteria['type']==2 ? X_code( $reviewDO->getCityId() ) : 
																									X_code( $reviewDO->getCountryId() ) ) )?>" />
			<input type="hidden" name="kwrds"      value="<?php echo $criteria['kwrds']; ?>" />
			<input type="hidden" name="langCode"   value="<?php echo $criteria['langCode']; ?>" />
			<input type="hidden" name="reviewType" value="<?php echo $criteria['type']; ?>" />
			<input type="hidden" name="onlyImg"    value="<?php echo $criteria['onlyImg']; ?>" />
			<input type="hidden" name="onlyMov"    value="<?php echo $criteria['onlyMov']; ?>" />
			<input type="hidden" name="onlyDoc"    value="<?php echo $criteria['onlyDoc']; ?>" />
			<!-- valorizzato dal javascript dell'onChange -->
			<input id="orderType" type="hidden" name="orderType"  value="" />
		</form>
	<?php } ?>
	
</div>
