const vm = new Vue({
  el: '#app',
  name: "postLists", 
  template: `
  <div id="app">
  <div>
    <button type="button" @click="add">Add</button>
    <button type="button" @click="remove">Remove</button>
    <button type="button" @click="reverse">Sortieren</button>
  </div>
  <transition-group tag="ul" name="list">
    <li v-for="item in list" :key="item.id">
      id: {{ item.id }}<br/>
      title:  {{ item.title }}
      <!--<button class="btn btn-danger" @click="deletePost(item.id)" >Delete</button>-->
      <a class="btn btn-danger" :href="pathDelete.replace('placeholderId',item.id)">Delete</a>
    </li>
  </transition-group>
</div>
  `,
  data: {
    pathShow: "",
    pathDelete: "",
    posts:"",
    maxId: 3,
    list: []
  },
  beforeMount: function () {
    this.posts = JSON.parse(this.$el.attributes['data-posts'].value);
    this.pathShow = this.$el.attributes['data-pathShow'].value;
    this.pathDelete = this.$el.attributes['data-pathDelete'].value;
    console.log("BEFORE MOUNT: ",typeof this.pathShow,"\n",this.pathDelete);
  },
  mounted() {
    // console.log("MOUNTED");
    this.t = setInterval(() => {
      this.intervallFunction();
    }, 500);
    console.log(this.posts);
  },
  methods: {
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