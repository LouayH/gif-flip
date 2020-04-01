<template>
    <section id="single-gif">
        <video controls :src="gif.images.original_mp4.mp4"></video>
        <div id="actions">
            <div id="favorite" class="action">
                <span class="icon outline-heart"></span> Favorite this GIF
            </div>
            <div id="share-link" class="action">
                <span class="icon link"></span> Share GIF Link
            </div>
        </div>
    </section>
</template>

<script>
export default {
    props: {
        gif: {
            type: Object,
            required: true
        }
    }
}
</script>

<style lang="scss" scoped>
#single-gif {
    video {
        outline: 0;
        border: solid 2px #ecf0f1;
        border-radius: 255px 15px 225px 15px/15px 225px 15px 255px;
        width: 100%;
        max-width: 480px;
    }

    #actions {
        display: flex;
        justify-content: space-between;
        margin-top: 1rem;
        color: #ecf0f1;

        .action {
            display: flex;

            .icon {
                display: inline-block;
                margin-right: .5rem;
                width: 1rem;
                height: 1rem;
                mask-size: cover;

                &.link {
                    mask: url('../../images/link.svg');
                    background: #ecf0f1;
                }

                &.outline-heart {
                    mask: url('../../images/outline-heart.svg');
                    background: #ecf0f1;
                }

                &.heart {
                    mask: url('../../images/heart.svg');
                    background: #e74c3c;
                }
            }
        }
    }
}
</style>
