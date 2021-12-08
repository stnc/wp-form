<div class="stnc_box">
    <style scoped>
        .stnc_box{
            display: grid;
            grid-template-columns: max-content 1fr;
            grid-row-gap: 10px;
            grid-column-gap: 20px;
        }
        .stnc_field{
            display: contents;
        }
    </style>

<p class="meta-options stnc_field">
        <label for="quiz_id">Quiz Id</label>
        <input id="quiz_id"
            type="number"
            name="quiz_id"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'quiz_id', true ) ); ?>">
    </p>

    
    <p class="meta-options stnc_field">
        <label for="youtubeLink">Youtube Link</label>
        <input id="youtubeLink"
            type="text"
            name="youtubeLink"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'youtubeLink', true ) ); ?>">
    </p>


</div>