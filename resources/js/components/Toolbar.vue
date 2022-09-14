<template>
    <div class="toolbar" :class="{visible: toolbarVisible}">
        <button class="icon-btn h-ripple green" style="background: #06D6A0" @click="clearCurrentLink"><i class="fa fa-undo-alt"></i></button>
        <button id="copy-to-clipboard" data-clipboard-target="#clipboard-target" class="icon-btn h-ripple blue" :disabled="copied" :class="{'primary-color': copied}">
            <i class="fa fa-copy" v-show="!copied"></i>
            <i class="fa fa-check" v-show="copied"></i>
        </button>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import $ from 'jquery';
    import ClipboardJS from 'clipboard';

    export default{
        methods: {
            clearCurrentLink(){
                this.$store.commit('TopBar/updateLink', '');
                this.$root.$emit('linkInputFocus');
                this.$store.dispatch('Content/updateFormattedData', {
                    formattedHeading: null,
                    formattedContent: null,
                    cached: null,
                });
            },
        },

        mounted(){
            var clipboard = new ClipboardJS('#copy-to-clipboard');
            clipboard.on('success', () => {
                this.copied = true;
                setTimeout(() => {
                    this.copied = false;
                }, 3000);
            });

            clipboard.on('error', () => {
                alert('Looks like your browser doesn\'t support clipboard access. Try copying it yourself');
            });
        },

        data(){
            return {
                toolbarVisible: false,
                copied: false,
            }
        },

        computed: {
            ...mapState('Content', [
                'formattedData',
            ]),

            hasFormattedContent(){
                if (!this.formattedData.formattedContent) {
                    return false;
                } else {
                    return true;
                }
            },
        },
        watch: {
            hasFormattedContent(){
                if (!this.formattedData.formattedContent) {
                    this.toolbarVisible = false;
                } else {
                    setTimeout(() => {
                        this.toolbarVisible = true;
                    }, 300)
                }
            }
        },
    }
</script>

<style lang="scss">
    @import "../../sass/vue-sass/imports";

    .toolbar{
        position: fixed;
        top: 50%;
        left: 0;
        width: 40px;
        overflow: hidden;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
        z-index: $zIndex-toolbar;
        transform: translate(-100%, -50%);
        transition: transform .2s;
        button{
            border-radius: 0;
            &.select-all-trigger{
                &.all-selected{
                    background: white;
                    color: $blue;
                }
            }
        }
        &.visible{
            transition: all .2s;
            transform: translate(0, -50%);
        }
    }
</style>
