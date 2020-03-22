<template>
    <div class="container">
        <div class="row justify-content-center">
          <div>
            <button class="grayscale-photo btn btn-secondary" @click="grayscalePhotos()">Grayscale Photos</button>
            <button class="color-photo btn btn-success" @click="colorPhotos()">Color Photos</button>
            <br><br>
          </div>
          
            <div class="col-md-12 row justify-content-center">
                <div class="card col-md-5" v-for="photo in photos"  v-bind:key="photo.id">
                    <img v-bind:src="photo.image_url" v-bind:width="photo.width" v-bind:height="photo.height" alt="" grayscale="false">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
      data() {
        return {
          errors: {},
          photos: {},
        }
      },
      mounted() {
          console.log('Component mounted.')
          $('.color-photo').hide();
      },
      methods: {
        getPhotos(){
          axios.get('/photos/index')
                .then((response)=>{
                this.photos = response.data.photos
                })
          },
        grayscalePhotos() {
          $('img').attr('grayscale', true);
          for (let photo of this.photos) {
            photo.image_url += '?grayscale'
          }
          $('.grayscale-photo').hide();
          $('.color-photo').show();
        },
        colorPhotos() {
          $('img').attr('grayscale', false);
          for (let photo of this.photos) {
            let originalUrl = photo.image_url.replace('?grayscale', '')
            photo.image_url = originalUrl
          }
          $('.grayscale-photo').show();
          $('.color-photo').hide();
        }
      },
      created() {
        this.getPhotos()
      }
    }
</script>