<article class="section section--scrollable section--<?php echo $section; ?> align--center theme--secondary" id="section-<?php echo $section; ?>">

    <h3 class="section__header">Open Roles</h3>

    <?php /*
    <section class="media-blocks-wrap media-blocks-wrap--scrollable tabs flexbox hide-scrollbar scroll-touch drag-scroll">

        <?php $n=0; while(have_rows('vacancies')): the_row(); $n++; ?>

        <div class="media-block align--left anim-500 anim-d-<?php echo $n; ?>00" data-animate="fadeInUp">

            <h3 class="media-block__title"><?php the_sub_field('job_title'); ?></h3>

            <?php if(get_sub_field('salary')): ?>
            <p class="media-block__meta"><?php the_sub_field('salary'); ?></p>
            <?php endif; ?>

            
            <?php if(get_sub_field('description')): ?>
            <div class="media-block__desc">
                <?php the_sub_field('description'); ?>
            </div>
            <?php endif; ?>

            <?php if(get_sub_field('job_spec')): ?>
            <a target="_blank" class="cta cta--md cta--pink" href="<?php echo get_sub_field('job_spec')['url'] ?>">Job Spec</a>
            <?php endif; ?>

        </div>

        <?php endwhile; ?>

    </section>
    */ ?>
    
    <div style="margin-top:30px" id="recruitee-careers"></div><script type="text/javascript"> var rtscript = document.createElement('script');  rtscript.type = 'text/javascript';  rtscript.onload = function() { var widget = new RTWidget({
    "companies": [
    43564
    ],
    "detailsMode": "popup",
    "language": "en",
    "departmentsFilter": [],
    "themeVars": {
    "primary": "#1999e3",
    "secondary": "#000",
    "text": "#5c6f78",
    "textDark": "#37474f",
    "fontFamily": "\"Helvetica Neue\", Helvetica, Arial, \"Lucida Grande\", sans-serif;",
    "baseFontSize": "16px"
    },
    "flags": {
    "showLocation": true,
    "showCountry": true,
    "showCity": true,
    "groupByLocation": false,
    "groupByDepartment": true,
    "groupByCompany": false
    }
    }) }; rtscript.src = 'https://d10zminp1cyta8.cloudfront.net/widget.js'; document.body.appendChild(rtscript);</script>

</article>