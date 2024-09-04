<?php
include "../include/connection.php"; 
$date_now = date('Y-m-d');
$news_query = "select top 10 *,datename(dw,postDate)+', '+right('00'+day(postDate),2)+' '+datename(m,postDate)+' '+convert(varchar(4),year(postDate))as PostDate from tr_news a,ms_newsCat b, lk_newsLocation c where a.newsCatCode=b.newsCatCode and b.newsLocationCode=c.newsLocationCode and a.isActive='1' and a.isFeatured='1' and a.newsCatCode='News' and c.newsLocationCode='o' and (a.startDate <= '$date_now' and a.endDate >='$date_now') order by priority desc, newsID desc";
$News=odbc_exec($conn,$news_query);

//echo"test";

			
				
    while(odbc_fetch_row($News)){
    $id=odbc_result($News,1);
    $Title=odbc_result($News,3);
    $NewsImageName=odbc_result($News,8);
//    echo '<a href="news.php?id='.$id .'"" title="'.$Title.'" target="_blank"><img src="../template/1-ViewImages.php?id='.$id.'" alt="Slide 1"></a>';
    ?>
    <div class="swiper-slide">
        <div class="swiper-zoom-container">
            <img alt="<?=$Title?>" src="https://hr.kompasgramedia.com/template/1-ViewImages.php?id=<?=$id?>" />
        </div>
    </div>     
    <?php
    }
//    echo"test2";
			
			?>
				
