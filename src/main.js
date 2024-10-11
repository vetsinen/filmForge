document.addEventListener('alpine:init', () => {
    Alpine.data('filmForge', () => ({
        greeting: 'filmforge app ',
        addingFilmMode: this.userid > 0,

        title: '',
        release_year: 2020,
        format: 'VHS',
        actors: 'Tom Hanks, Leonardo DiCaprio, Meryl Streep, Denzel Washington, Jennifer Lawrence',
        items: [],

        username: 'root',
        password: 'supagudVHS',
        password2: 'supagudVHS',
        userid: 0,
        authMode: false,
        url: 'api.php/films',
        randomFilmTitle: function () {
            const adjectives = ['beautiful', 'colorful', 'mysterious', 'ancient', 'modern'];
            const nouns = ['landscape', 'adventure', 'journey', 'dream', 'experience'];

            const getRandomItem = array => array[Math.floor(Math.random() * array.length)];

            const randomSentence = `The ${getRandomItem(adjectives)} ${getRandomItem(nouns)} is always a ${getRandomItem(adjectives)} ${getRandomItem(nouns)}.`;

            return randomSentence;
        },
        initData: async function (url = 'api.php/films') {
            this.title = this.randomFilmTitle();
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const rez = await response.json();
                console.log(rez.items);
                this.items = rez.items;
            } catch (error) {
                console.error('Error:', error.message);
            }
        },
        getFilmDetails: async function (id) {
            const response = await fetch('api.php/films/' + id);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const rez = await response.json();
            alert('film actors: ' + rez.actors);
        },
        postData: async function () {
            const response = await fetch('api.php/films', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    title: this.title,
                    release_year: this.release_year,
                    format: this.format,
                    actors: this.actors
                })
            });
            this.initData();
            this.addingFilmMode = false;
        },
        deleteFilm: async function (id) {
            const response = await fetch(this.url + '/' + id, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            this.initData();
        },
        login: async function () {
            const response = await fetch('api.php/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({username: this.username, password: this.password})
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const rez = await response.json();
            console.log(rez);
            if (rez.status === 'ok') {
                this.userid = rez.id;
                this.authMode = false
            }
        },
        register: async function () {
            if (this.password !== this.password2) {
                alert('please, confirm a password');
                return;
            }
            const response = await fetch('api.php/auth/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({username: this.username, password: this.password})
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const rez = await response.json();
            console.log(rez);
            if (rez.status === 'ok') {
                this.userid = rez.id;
                this.authMode = false;
            }
        },
        logout: async function () {
            this.userid = 0;
            const response = await fetch('api.php/auth/logout');
        },
        ffocus: function (id) {
            const film = document.getElementById(id)
            //document.getElementById(id).focus({ focusVisible: true });
            film.scrollIntoView();
        }
    }))
})