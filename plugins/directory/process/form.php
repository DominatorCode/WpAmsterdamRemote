<div class="rates_box">

    <style scoped>
        .rates_box{
            display: grid;
            grid-template-columns: max-content 2fr;
            grid-row-gap: 2px;
            grid-column-gap: 20px;
        }
        .rates_filed{
            display: contents;
        }
        ul.rate-option li {
            display: inline;
            margin-right: 10px;
        }
        .rate-option input[type="number"] {
            text-align: right;
            font-size: 1.25em;
        }
    </style>

    <!-- Rates -->
    <h4>Additional Hour Admin:</h4>
    <div class="meta_options">
        <ul class="rate-option">
        <li>
        <label for="add_hour_in">IN:</label>
        <input 
            id="add_hour_in" 
            type="number"
            name="add_hour_in"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'add_hour_in', true) ); ?>"
        >
        </li>
        <li>
        <label for="add_hour_out">OUT:</label>
        <input 
            id="add_hour_out" 
            type="number"
            name="add_hour_out"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'add_hour_out', true) ); ?>"
        >
        </li>
        </ul>
    </div>

    <h4>1 Hour:</h4>
    <div class="meta_options">
        <ul class="rate-option">
        <li>
        <label for="one_hour_in">IN:</label>
        <input 
            id="one_hour_in" 
            type="number"
            name="one_hour_in"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'one_hour_in', true) ); ?>"
        >
        </li>
        <li>
        <label for="one_hour_out">OUT:</label>
        <input 
            id="one_hour_out" 
            type="number"
            name="one_hour_out"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'one_hour_out', true) ); ?>"
        >
        </li>
        </ul>
    </div>
    
    <h4>Dinner Date:</h4>
    <div class="meta_options">
        <ul class="rate-option">
        <li>
        <label for="dinner_date_in">IN:</label>
        <input 
            id="dinner_date_in" 
            type="number"
            name="dinner_date_in"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'dinner_date_in', true) ); ?>"
        >
        </li>
        <li>
        <label for="dinner_date_out">OUT:</label>
        <input 
            id="dinner_date_out" 
            type="number"
            name="dinner_date_out"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'dinner_date_out', true) ); ?>"
        >
        </li>
        </ul>
    </div>

    <h4>Overnight:</h4>
    <div class="meta_options">
        <ul class="rate-option">
        <li>
        <label for="overnight_in">IN:</label>
        <input 
            id="overnight_in" 
            type="number"
            name="overnight_in"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'overnight_in', true) ); ?>"
        >
        </li>
        <li>
        <label for="overnight_out">OUT:</label>
        <input 
            id="overnight_out" 
            type="number"
            name="overnight_out"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'overnight_out', true) ); ?>"
        >
        </li>
        </ul>
    </div>
    

</div>