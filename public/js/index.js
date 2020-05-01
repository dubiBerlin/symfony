

const vm = new Vue({
  el: '#app',
  name: "postLists", 
  template: `
  <div>
  <div class="post-btn-container" >
    <button type="button" @click="getRandomPost">Get Random Post</button>
    <button type="button" @click="reverse">Reverse posts</button>
  </div>
  <transition-group tag="div" name="list">
    <div class="post-item"  v-for="item in list" :key="item.id">
      <div class="post-item-header" >
        <img v-if="profile_image"  class="post-item-profile-img" :src="profile_image_path"  >
        <i v-else class="fa fa-user-circle fa-2x" style="margin-top:4px" > </i>    
        <div class="post-item-header-info">
          <span> {{profile_username}}  </span>
          <i class="fa fa-camera"></i>
          <i class="fa fa-angellist"></i>
          <span>@asksk</span>
        </div>
      </div>
      
      <div class="post-item-body" >
        <div @click="showPost(item.id)" >{{item.title}}</div>
        <div>{{item.message}}</div>
        <img  v-show="item.image"  :src="uploadFolder + item.image"  >
      </div>

      <div class="post-item-footer" >
        <div class="post-item-footer-likes">
          <i class="fa fa-heart"></i> <span>{{item.likes }}</span>
        </div>
        <span>{{formatCreatedAt(item.created_at.date)}}</span>
        <i class="fa fa-question-circle"></i>
      </div>
    </div>

  </transition-group>
</div>
  `,
  data: {
    pathShow: "",
    pathDelete: "",
    posts:"",
    profile_image:"",
    profile_image_path:"",
    uploadFolder:"",
    profile_username:"",
    list: []
  },
  beforeMount: function () {
    this.posts = JSON.parse(this.$el.attributes['data-posts'].value);
    this.posts = this.posts.map(post => { return { ...post, likes: this.random(7000)}}); 
    this.pathShow = this.$el.attributes['data-pathShow'].value;
    this.pathDelete = this.$el.attributes['data-pathDelete'].value;
    this.uploadFolder = this.$el.attributes['data-upload-folder'].value;
    this.profile_image = this.$el.attributes['data-profile-image'].value;
    this.profile_image_path = `${this.uploadFolder}${this.profile_image}`;
    this.profile_username = this.$el.attributes['data-profile-username'].value;
  },
  mounted() {
    this.t = setInterval(() => {
      this.intervallFunction();
    }, 1000);
  },
  methods: {
     showPost(id, event) {
       window.open(this.pathShow.replace("placeholderId", id), "_self");
     },
    formatCreatedAt(createdAtObj){
      let createdAtSplitted = createdAtObj.split(" ");
      this.formatCreatedAtDate(createdAtSplitted[0]);
      let time = createdAtSplitted[1];
      return `${this.formatCreatedAtDate(createdAtSplitted[0])} - ${time.split(".")[0]}`; 
    },
    formatCreatedAtDate(date){
      date = date.split("-");
      return `${date[2]}.${date[1]}.${date[0]}`;
    },
    reverse() {
      this.list.reverse();
    },
    random(max) {
      return Math.floor(Math.random() * (max + 1))
    },
    intervallFunction() {
      if (this.posts.length > 0) {
        this.list.push(this.posts.pop());
      } else {
        window.clearInterval(this.t);
      }
    },
    getRandomPost(){
     ;(async () => {
        const response = await fetch('http://localhost/sfcourse/public/index.php/post/random')
        const data = await response.json();

        let post = data.randomPost;

        post.id = this.random(2000);// to avoid duplicate keys in loop
        
        this.list.push( {
          ...post,
          likes: this.random(7000)
        });
      })()

    }
  }
});