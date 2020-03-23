<template>
    <div class="container">
        <div class="row justify-content-center">
          <div class="row col-md-12 justify-content-center button-group">
            <button class="grayscale-photo btn btn-secondary" @click="grayscalePhotos()">Grayscale Photos</button>
            <button class="color-photo btn btn-success" @click="colorPhotos()">Color Photos</button>
            <br><br>
          </div>
          <div class="row justify-content-center filter-group">
            <select class="dimension-select" name="" id="" @change="setDimensions">
              <option value="">Select Dimensions</option>
              <option value="100x100" w-val="100" h-val="100">100 x 100</option>
              <option value="250x250" w-val="250" h-val="250">250 x 250</option>
              <option value="300x200" w-val="300" h-val="200">300 x 200</option>
              <option value="300x300" w-val="300" h-val="300">300 x 300</option>
              <option value="400x200" w-val="400" h-val="200">400 x 200</option>

            </select>
          </div>
            <div class="col-md-12 row justify-content-center">
                <div class="card col-md-5 col-sm-12 justify-content-center" v-for="photo in photosInfo"  v-bind:key="photo.id">
                    <img v-bind:src="photo.image_url" v-bind:width="photo.width" v-bind:height="photo.height" alt="" grayscale="false">
                </div>
            </div>
                                            <!-- Pagination Links -->
                <div class="row" >
                  <div class="col-sm-12" id="pagination-link-container">
                    <pagination
                      :data="this.photos"
                      v-on:pagination-change-page="getPhotos"
                    ></pagination>
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
          photosInfo: {},
          height: 100,
          width: 100
        }
      },
      mounted() {
          console.log('Component mounted.')
          $('.color-photo').hide();
      },
      methods: {
        getPageResults(page = 1) {
          this.getPhotos(page)
        },
        getPhotos(
          page = 1
        ){
          let url = '/photos/index'
          url+= '?page=' + page
          axios.get(url)
                .then((response)=>{
                this.photos = response.data
                this.photosInfo = response.data.data
                $('.grayscale-photo').show();
                $('.color-photo').hide();
                })
                .catch(error => {
                  console.log(error)
                })
          },
        getPhotosByDimension(width = this.width, height = this.height){
          let url = '/photos/index/' + width + '/' + height
          axios.get(url)
            .then((response)=>{
              this.photos = response.data
              this.photosInfo = response.data.data
              $('.grayscale-photo').show();
              $('.color-photo').hide();
            })
            .catch(error => {
              console.log(error)
            })

        },
        setDimensions() {
          let val = $('.dimension-select').val();
          switch (val) {
            case '100x100': 
              this.width = 100
              this.height = 100
              this.getPhotosByDimension()
              break;
            case '250x250':
              this.width = 250
              this.height = 250
              this.getPhotosByDimension()
              break;
            case '300x200':
              this.width = 300
              this.height = 200
              this.getPhotosByDimension()
              break;    
            case '300x300':
              this.width = 300
              this.height = 300
              this.getPhotosByDimension()
              break;    
            case '400x200':
              this.width = 400
              this.height = 200
              this.getPhotosByDimension()
              break;  
            default:
                this.getPhotos()
                                            
          }
        },
        grayscalePhotos() {
          $('img').attr('grayscale', true);
          for (let photo of this.photosInfo) {
            photo.image_url += '?grayscale'
          }
          $('.grayscale-photo').hide();
          $('.color-photo').show();
        },
        colorPhotos() {
          $('img').attr('grayscale', false);
          for (let photo of this.photosInfo) {
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