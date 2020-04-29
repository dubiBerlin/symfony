const vm = new Vue({
  el: '#app',
  name: "postLists", 
  props: {
    posts: {
      type: Array,
      required: true
    }
  },
  template: `
  <div id="app">
  <div>
    <button type="button" @click="add">Add</button>
    <button type="button" @click="remove">Remove</button>
    <button type="button" @click="shuffle">Shuffle</button>
    <button type="button" @click="reverse">Sortieren</button>
  </div>
  <transition-group tag="ul" name="list">
    <li v-for="item in list" :key="item.id">
      Item {{ item.id }}
    </li>
  </transition-group>
</div>
  `,
  data: {
    maxId: 3,
    list: [],
    db: [
      { id: 1 },
      { id: 2 },
      { id: 3 },
      { id: 4 },
      { id: 5 }
    ]
  },
  mounted() {
    this.t = setInterval(() => {
      this.intervallFunction();
    }, 1000);
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
      console.log("hello")
      if (this.db.length > 0) {
        this.list.push(this.db.pop());
      } else {
        window.clearInterval(this.t);
      }
    },
    add() {
      const id = ++this.maxId
      const index = this.random(this.list.length)
      this.list.splice(index, 0, { id })
    },
    remove() {
      const index = this.random(this.list.length - 1)
      this.list.splice(index, 1)
    },
    shuffle() {
      const shuffled = []
      while (this.list.length > 0) {
        const index = this.random(this.list.length - 1)
        shuffled.push(this.list[index])
        this.list.splice(index, 1)
      }
      this.list = shuffled
    }
  }
});