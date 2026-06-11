const cities = {
    madrid: { name: 'Madrid', latitude: 40.4168, longitude: -3.7038 },
    barcelona: { name: 'Barcelona', latitude: 41.3874, longitude: 2.1686 },
    valencia: { name: 'Valencia', latitude: 39.4699, longitude: -0.3763 },
    sevilla: { name: 'Sevilla', latitude: 37.3891, longitude: -5.9845 },
    bilbao: { name: 'Bilbao', latitude: 43.2630, longitude: -2.9350 },
    malaga: { name: 'Málaga', latitude: 36.7213, longitude: -4.4214 }
};

const weatherDescriptions = {
    0: 'Vedro',
    1: 'Pretežno vedro',
    2: 'Djelomično oblačno',
    3: 'Oblačno',
    45: 'Magla',
    48: 'Magla s injem',
    51: 'Slaba rosulja',
    53: 'Umjerena rosulja',
    55: 'Jaka rosulja',
    56: 'Slaba ledena rosulja',
    57: 'Jaka ledena rosulja',
    61: 'Slaba kiša',
    63: 'Umjerena kiša',
    65: 'Jaka kiša',
    66: 'Slaba ledena kiša',
    67: 'Jaka ledena kiša',
    71: 'Slab snijeg',
    73: 'Umjeren snijeg',
    75: 'Jak snijeg',
    77: 'Zrnati snijeg',
    80: 'Slabi pljuskovi',
    81: 'Umjereni pljuskovi',
    82: 'Jaki pljuskovi',
    85: 'Slabi snježni pljuskovi',
    86: 'Jaki snježni pljuskovi',
    95: 'Grmljavinsko nevrijeme',
    96: 'Nevrijeme sa slabom tučom',
    99: 'Nevrijeme s jakom tučom'
};

const citySelect = document.getElementById('city');
const statusMessage = document.getElementById('weather-status');
const weatherContent = document.getElementById('weather-content');
const forecastGrid = document.getElementById('forecast-grid');

function describeWeather(code) {
    return weatherDescriptions[code] || 'Nepoznati uvjeti';
}

function formatDay(dateString) {
    const date = new Date(`${dateString}T12:00:00`);

    return new Intl.DateTimeFormat('hr-HR', {
        weekday: 'short',
        day: 'numeric',
        month: 'short'
    }).format(date);
}

function formatTime(dateString) {
    const date = new Date(dateString);

    return new Intl.DateTimeFormat('hr-HR', {
        weekday: 'long',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
}

function renderCurrentWeather(city, current) {
    document.getElementById('current-city').textContent = city.name;
    document.getElementById('current-condition').textContent = describeWeather(current.weather_code);
    document.getElementById('current-time').textContent = `Ažurirano: ${formatTime(current.time)}`;
    document.getElementById('current-temperature').textContent = Math.round(current.temperature_2m);
    document.getElementById('apparent-temperature').textContent =
        `${Math.round(current.apparent_temperature)} °C`;
    document.getElementById('humidity').textContent = `${current.relative_humidity_2m} %`;
    document.getElementById('wind-speed').textContent =
        `${Math.round(current.wind_speed_10m)} km/h`;
}

function renderForecast(daily) {
    forecastGrid.replaceChildren();

    daily.time.forEach((date, index) => {
        const card = document.createElement('article');
        card.className = 'forecast-card';

        const day = document.createElement('h3');
        day.textContent = formatDay(date);

        const condition = document.createElement('p');
        condition.className = 'forecast-condition';
        condition.textContent = describeWeather(daily.weather_code[index]);

        const temperatures = document.createElement('p');
        temperatures.className = 'forecast-temperatures';

        const maximum = document.createElement('strong');
        maximum.textContent = `${Math.round(daily.temperature_2m_max[index])}°`;

        const minimum = document.createElement('span');
        minimum.textContent = `${Math.round(daily.temperature_2m_min[index])}°`;

        temperatures.append(maximum, minimum);

        const rain = document.createElement('p');
        rain.className = 'forecast-rain';
        rain.textContent = `Oborine: ${daily.precipitation_probability_max[index]} %`;

        card.append(day, condition, temperatures, rain);
        forecastGrid.append(card);
    });
}

async function loadWeather(cityKey) {
    const city = cities[cityKey];
    const parameters = new URLSearchParams({
        latitude: city.latitude,
        longitude: city.longitude,
        current: [
            'temperature_2m',
            'relative_humidity_2m',
            'apparent_temperature',
            'weather_code',
            'wind_speed_10m'
        ].join(','),
        daily: [
            'weather_code',
            'temperature_2m_max',
            'temperature_2m_min',
            'precipitation_probability_max'
        ].join(','),
        timezone: 'Europe/Madrid',
        forecast_days: '7'
    });

    statusMessage.textContent = 'Učitavanje prognoze...';
    statusMessage.classList.remove('weather-error');
    statusMessage.hidden = false;
    weatherContent.hidden = true;

    try {
        const response = await fetch(`https://api.open-meteo.com/v1/forecast?${parameters}`);

        if (!response.ok) {
            throw new Error(`Open-Meteo odgovor: ${response.status}`);
        }

        const data = await response.json();
        renderCurrentWeather(city, data.current);
        renderForecast(data.daily);

        statusMessage.hidden = true;
        weatherContent.hidden = false;
    } catch (error) {
        console.error(error);
        statusMessage.textContent =
            'Prognozu trenutačno nije moguće dohvatiti. Pokušajte ponovno kasnije.';
        statusMessage.classList.add('weather-error');
    }
}

citySelect.addEventListener('change', () => {
    loadWeather(citySelect.value);
});

loadWeather(citySelect.value);
