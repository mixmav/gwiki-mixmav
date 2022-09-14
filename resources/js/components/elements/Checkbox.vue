<template>
    <label class="md-checkbox">
        <input type="checkbox" :checked="checked" @input="$emit('input', $event.target.checked)">
        <span v-html="label"></span>
    </label>
</template>

<script>
    export default {
        props: [
            'label',
            'checked',
        ]
    }
</script>

<style lang="scss">
    @import "../../../sass/vue-sass/imports";

    $md-checkbox-margin: 20px 0;
    $md-checkbox-checked-color: $primary-color;
    $md-checkbox-border-color: rgba(black, 0.6);
    $md-checkbox-border-color-disabled: rgba(0, 0, 0, 0.26);
    $md-checkbox-checked-color-disabled: rgba(0, 0, 0, 0.26);

    $md-checkbox-size: 20px;
    $md-checkbox-padding: 4px;

    $md-checkmark-width: 2px;
    $md-checkmark-color: #fff;

    .md-checkbox{
    	position: relative;
    	margin: $md-checkbox-margin;
    	display: inline-block;
    	@extend .no-select;

    	&.disabled{
    		pointer-events: none;
    		label{
    			color: rgba(black, 0.5);
    		}
    	}

    	span{
    		cursor: pointer;
    		color: $blue;
    		display: inline-block;
    		font: {
    			weight: 700;
    		}
    		i{
    			color: inherit;
    			margin-right: 5px;
    			position: relative;
    			bottom: 2px;
    		}
    		margin-left: 20px;
    		margin-top: 3px;
    		&:before, &:after{
    			content: "";
    			position: absolute;
    			left:0;
    			top: 0;
    		}
    		&:before {
    			width: $md-checkbox-size;
    			height: $md-checkbox-size;
    			background: #fff;
    			border: 2px solid $md-checkbox-border-color;
    			border-radius: 2px;
    			cursor: pointer;
    			transition: background .3s;
    		}

    		&:after {
    		 // checkmark
    		}
    	}

    	input[type="checkbox"] {
    		outline: 0;
    		margin-right: $md-checkbox-size - 10px;
    		visibility: hidden;

    		&:checked {
    			+ span:before{
    				background: $md-checkbox-checked-color;
    				border:none;
    			}
    			+ span:after {
    				$md-checkmark-size: $md-checkbox-size - 2*$md-checkbox-padding;
    				transform: rotate(-45deg);
    				top: ($md-checkbox-size / 2) - ($md-checkmark-size / 4) - $md-checkbox-size/10;
    				left: $md-checkbox-padding;
    				width: $md-checkmark-size;
    				height: $md-checkmark-size / 2;
    				border: $md-checkmark-width solid $md-checkmark-color;
    				border-top-style: none;
    				border-right-style: none;
    			}
    		}

    		&:disabled {
    			+ label:before{
    				border-color: $md-checkbox-border-color-disabled;
    			}

    			&:checked {
    				+ label:before{
    					background: $md-checkbox-checked-color-disabled;
    				}
    			}
    		}
    	}
    }
</style>
