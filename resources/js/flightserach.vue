<template>
    <div class="flight-search">
      <h2>Welcome to Flight Search</h2>
      <form @submit.prevent="searchFlights">
        <div class="form-group">
          <label for="boardingLocation">Boarding Location:</label>
          <input
            type="text"
            id="boardingLocation"
            v-model="boardingLocation"
            required
          />
        </div>
        <div class="form-group">
          <label for="destinationLocation">Destination Location:</label>
          <input
            type="text"
            id="destinationLocation"
            v-model="destinationLocation"
            required
          />
        </div>
        <div class="form-group">
          <label for="dateOfTravel">Date of Travel:</label>
          <input
            type="date"
            id="dateOfTravel"
            v-model="dateOfTravel"
            required
          />
        </div>
        <button type="submit">Search Flights</button>
      </form>
  
      <div v-if="flights.length" class="flight-results">
        <h3>Available Flights</h3>
        <table>
          <thead>
            <tr>
              <th>Flight Name</th>
              <th>Takeoff Location</th>
              <th>Landing Location</th>
              <th>Operating Days</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="flight in flights" :key="flight.id">
              <td>{{ flight.flight_name }}</td>
              <td>{{ flight.takeoff_location }}</td>
              <td>{{ flight.landing_location }}</td>
              <td>{{ flight.operating_days }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else-if="searchDone">
        <p>No flights available for your search criteria.</p>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'FlightSearch',
    data() {
      return {
        boardingLocation: '',
        destinationLocation: '',
        dateOfTravel: '',
        flights: [],
        searchDone: false,
      };
    },
    methods: {
      async searchFlights() {
        try {
          const response = await axios.get('http://127.0.0.1:8000/api/flights', {
            params: {
              boardingLocation: this.boardingLocation,
              destinationLocation: this.destinationLocation,
              dateOfTravel: this.dateOfTravel,
            },
          });
  
          this.flights = response.data; // assuming response data contains the list of flights
          this.searchDone = true; // flag to indicate search completion
        } catch (error) {
          console.error('Error fetching flights:', error);
          this.flights = [];
          this.searchDone = true; // flag to indicate search completion even on error
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .flight-search {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    background-color: #f9f9f9;
  }
  
  .form-group {
    margin-bottom: 15px;
  }
  
  label {
    display: block;
    margin-bottom: 5px;
  }
  
  input[type="text"],
  input[type="date"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  
  button {
    padding: 10px 15px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #0056b3;
  }
  
  .flight-results {
    margin-top: 20px;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
  }
  
  th, td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ccc;
  }
  
  th {
    background-color: #007bff;
    color: white;
  }
  
  tr:nth-child(even) {
    background-color: #f2f2f2;
  }
  </style>
  