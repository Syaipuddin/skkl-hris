<?php

//$News=odbc_exec($conn,"select top 5 *,datename(dw,postDate)+', '+right('00'+day(postDate),2)+' '+datename(m,postDate)+' '+convert(varchar(4),year(postDate))as PostDate from tr_news a,ms_newsCat b, lk_newsLocation c where a.newsCatCode=b.newsCatCode and b.newsLocationCode=c.newsLocationCode and a.isActive='1' and a.isFeatured='1' and a.newsCatCode='News' and c.newsLocationCode='o' order by priority desc, newsID desc");

$date_now = date('Y-m-d');
// $news_query = "select top 5 *,datename(dw,postDate)+', '+right('00'+day(postDate),2)+' '+datename(m,postDate)+' '+convert(varchar(4),year(postDate))as PostDate from tr_news a,ms_newsCat b, lk_newsLocation c where a.newsCatCode=b.newsCatCode and b.newsLocationCode=c.newsLocationCode and a.isActive='1' and a.isFeatured='1' and a.newsCatCode='News' and c.newsLocationCode='o' and (a.startDate <= '$date_now' and a.endDate >='$date_now') order by priority desc, newsID desc";
$news_query = "select top 5 *,datename(dw,postDate)+', '+right('00'+day(postDate),2)+' '+datename(m,postDate)+' '+convert(varchar(4),year(postDate))as PostDate from tr_news a,ms_newsCat b, lk_newsLocation c where a.newsCatCode=b.newsCatCode and b.newsLocationCode=c.newsLocationCode and a.isActive='1' and a.isFeatured='1' and a.newsCatCode='News' and c.newsLocationCode='o' and (a.startDate <= ? and a.endDate >=?) order by priority desc, newsID desc";
// $News=odbc_exec($conn,$news_query);
odbc_execute($News=odbc_prepare($conn, $news_query), array($date_now, $date_now));

?>			

<div id="container" class="span6">
		<div id="example">
			<div id="slides">
				<div class="slides_container">
			<?php
			
				//echo '<a href="" title="HR Portal" target="_blank"><img src="img/slide/slide-1.jpg" alt="Slide 1"></a>';
				//echo '<a href="http://www.syukurankg.com" title="Syukuran KG | Beragam Warna Satu Cinta" target="_blank"><img src="img/slide/slide-1.jpg" alt="Slide 1"></a>';
				echo '<a href="test-video.php?KeepThis=true&TB_iframe=true&height=480&width=640" title="5C KG Movie" class="pull-right thickbox"><img src="../img/slide/slide-1.png" alt="5C KG Movie"></a>';
				$i=0;
				while(odbc_fetch_row($News)){
				$id=odbc_result($News,1);
				$Title=odbc_result($News,3);
				$NewsImageName=odbc_result($News,8);?>
				<form id="formNews_<?=$i?>" action="news.php" method="post">
					<!-- <h4><a href="javascript:;" onclick="document.getElementById('formNews_'+<?php echo $i ?>).submit();"><?php echo trim($Title)?></a></h4> -->
					<a href="javascript:;" title="<?=$Title?>" onclick="document.getElementById('formNews_'+<?php echo $i ?>).submit();" ><img src="../template/1-ViewImages.php?id=<?=$id?>" alt="Slide 1"></a>
					<input type="hidden" name="id" value="<?=$id?>"/>
				</form>

				<?php
				$i++;
				// echo '<a href="news.php?id='.$id .'"" title="'.$Title.'" target="_blank"><img src="../template/1-ViewImages.php?id='.$id.'" alt="Slide 1"></a>';
				}
			
			?>
				
				</div>
				<a href="#" class="prev"><img src="../../img/slide/arrow-prev.png" width="24" height="43" alt="Arrow Prev"></a>
				<a href="#" class="next"><img src="../../img/slide/arrow-next.png" width="24" height="43" alt="Arrow Next"></a>
			</div>
		</div>
		</div>
