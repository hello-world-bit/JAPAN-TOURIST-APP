<template>
    <div class="container">
        <div class="search-box">
            <i class="fas fa-location-dot"></i>
            <input 
                v-model="city" 
                class="city-input" 
                placeholder="Enter City" 
                @keyup.enter="fetchData" 
            />
            <button class="fetch-button" @click="fetchData">
                <i class="search-icon fas fa-magnifying-glass"></i>
            </button>
        </div>

        <div v-if="loading" class="loading">Loading...</div>

        <div v-if="error" class="error">{{ error }}</div>

        <div v-if="weather" class="weather-box">
            <img :src="getWeatherIcon(weather.list[0].weather[0].icon)" alt="Weather Icon" />
            <p class="temperature">
                {{ convertToCelsius(weather.list[0].main.temp) }}°C 
                ({{ convertToFahrenheit(weather.list[0].main.temp) }}°F)
            </p>
            <p class="description">{{ weather.list[0].weather[0].description }}</p>
        </div>

        <div v-if="weather" class="weather-details">
            <div class="humidity">
                <i class="fas fa-water"></i>
                <div class="text">
                    <span>{{ weather.list[0].main.humidity }}%</span>
                    <p>Humidity</p>
                </div>
            </div>
            <div class="wind">
                <i class="fas fa-wind"></i>
                <div class="text">
                    <span>{{ weather.list[0].wind.speed }} km/h</span>
                    <p>Wind Speed</p>
                </div>
            </div>
        </div>

        <div v-if="places" class="places-info">
            <h2 class="sub-title" style="text-align: center">Places To Visit in {{ city }}</h2>
            <ul>
                <li v-for="place in places.results" :key="place.fsq_id" class="place-item">
                    <div class="category-icon">
                        <img :src="place.categories[0].icon.prefix + '64' + place.categories[0].icon.suffix" alt="Category Icon" />
                    </div>
                    <div>
                        <strong>{{ place.name }}</strong> (<span>{{ place.categories[0].short_name }}</span>)
                    </div>
                    <div>
                        <p>Location: 
                            <a 
                                :href="'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(place.location.formatted_address)" 
                                target="_blank" 
                                class="address-link"
                            >
                                {{ place.location.formatted_address }}
                            </a>
                        </p>
                    </div>
                </li>
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
        getWeatherIcon(iconCode) {
            return `http://openweathermap.org/img/wn/${iconCode}@2x.png`;
        },
    },
};
</script>

