<div class="block block--boxed-image flexbox justify-content-center align-items-center theme--<?php echo $sectionImageTheme; ?>" <?php if($sectionImageTheme == "full-bleed"): echo "style='background-image:url(".$sectionImage['url']."); background-size:cover; background-position: center; min-height:250px;'"; endif; ?>>

    <div class="box-sub-container box-sub-container--image flexbox justify-content-center align-items-center anim-1000 anim-d-1100" data-animate="fadeInUp">

        <?php if($sectionImageTheme != "full-bleed"): ?>

            <?php if($sectionVideo): ?>
                <video autoplay muted class="rwd"><source src="<?php echo $sectionVideo['url'];?>" type="video/mp4"></video>
            <?php elseif($sectionImage): ?>
                <img loading="lazy" class="lazyload img--boxed" data-src="<?php echo $sectionImage['url'];?>" alt="<?php echo $sectionHeader; ?> illustration">
            <?php endif; ?>

        <?php endif; ?>

    </div>

</div>