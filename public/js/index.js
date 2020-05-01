

const vm = new Vue({
  el: '#app',
  name: "postLists", 
  template: `
  <div>
  <div>
    <button type="button" @click="add">Add</button>
    <button type="button" @click="remove">Remove</button>
    <button type="button" @click="reverse">Sortieren</button>
  </div>
  <transition-group tag="div" name="list">

    <div class="post-item" v-for="item in list" :key="item.id">
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
        <div>{{item.title}}</div>
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
    maxId: 3,
    list: [],
    randomNumbers:[]
  },
  beforeMount: function () {
    this.posts = JSON.parse(this.$el.attributes['data-posts'].value);
    this.posts = this.posts.map(post => { return { ...post, likes: Math.floor(Math.random() * 7000) + 1}}); 
    this.pathShow = this.$el.attributes['data-pathShow'].value;
    this.pathDelete = this.$el.attributes['data-pathDelete'].value;
    this.uploadFolder = this.$el.attributes['data-upload-folder'].value;
    this.profile_image = this.$el.attributes['data-profile-image'].value;
    this.profile_image_path = `${this.uploadFolder}${this.profile_image}`;
    this.profile_username = this.$el.attributes['data-profile-username'].value;
    console.log("PRofile image: ",this.profile_image);
    console.log(this.posts);
    console.log(this.profile_username);
  },
  mounted() {
    this.t = setInterval(() => {
      this.intervallFunction();
    }, 2000);
    console.log(this.posts);
  },
  methods: {
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
    deletePost(id,event){
      Window.open(this.pathDelete.replace("placeholderId",id));
    },
    add() {
      const id = ++this.maxId
      const index = this.random(this.list.length)
      this.list.splice(index, 0, { id })
    },
    remove() {
      const index = this.random(this.list.length - 1)
      this.list.splice(index, 1)
    }
  }
});