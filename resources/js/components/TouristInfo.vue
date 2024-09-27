<template>
    <div class="container">
        <h1 class="title">Travel Information for Japan</h1>
        <input 
            v-model="city" 
            class="city-input" 
            placeholder="Enter City" 
            @keyup.enter="fetchData" 
        />
        <button class="fetch-button" @click="fetchData">Get Info</button>

        <div v-if="loading" class="loading">Loading...</div>

        <div v-if="error" class="error">{{ error }}</div>

        <div v-if="weather" class="weather-info">
            <h2 class="sub-title">Weather in {{ city }}</h2>
            <p>
                <i class="fas fa-thermometer-half"></i>
                Temperature: {{ convertToCelsius(weather.list[0].main.temp) }}°C 
                ({{ convertToFahrenheit(weather.list[0].main.temp) }}°F)
            </p>
            <p>
                <i class="fas fa-umbrella"></i>
                Precipitation: {{ (weather.list[0].pop * 100).toFixed(0) }}%
            </p>
            <p>
                <i class="fas fa-tint"></i>
                Humidity: {{ weather.list[0].main.humidity }}%
            </p>
            <p>
                <i class="fas fa-wind"></i>
                Wind: {{ weather.list[0].wind.speed }} km/h
            </p>
            <p>
                <i class="fas fa-cloud-sun"></i>
                Weather: {{ new Date(weather.list[0].dt * 1000).toLocaleDateString('en-US', { weekday: 'long' }) }} 
                - {{ weather.list[0].weather[0].description }}
            </p>
            <p>
                <i class="fas fa-calendar-alt"></i>
                Date & Time: {{ formatDate(weather.list[0].dt * 1000) }}
            </p>
        </div>

        <div v-if="places" class="places-info">
            <h2 class="sub-title">Places To Visit in {{ city }}</h2>
            <ul>
                <li v-for="place in places.results" :key="place.id" class="place-item">{{ place.name }}</li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            city: '',
            weather: null,
            places: null,
            loading: false,
            error: null,
        };
    },
    methods: {
        fetchData() {
            this.loading = true;
            this.error = null;

            Promise.all([
                fetch(`/weather/${this.city}`),
                fetch(`/places/${this.city}`)
            ])
            .then(async (responses) => {
                const weatherResponse = responses[0];
                const placesResponse = responses[1];

                if (!weatherResponse.ok) {
                    throw new Error('Error fetching weather data');
                }
                if (!placesResponse.ok) {
                    throw new Error('Error fetching places data');
                }

                const weatherData = await weatherResponse.json();
                const placesData = await placesResponse.json();

                this.weather = weatherData;
                this.places = placesData;
            })
            .catch((err) => {
                this.error = err.message; 
            })
            .finally(() => {
                this.loading = false; 
            });
        },
        convertToCelsius(kelvin) {
            return (kelvin - 273.15).toFixed(1); 
        },
        convertToFahrenheit(kelvin) {
            return ((kelvin - 273.15) * 9/5 + 32).toFixed(1);
        },
        formatDate(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true,
                timeZone: 'Asia/Tokyo', 
            });
        },
    },
};
</script>
