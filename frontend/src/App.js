import React, { Component } from 'react';
import './App.css';
import axios from 'axios';
import { Button, Container, Card, Row } from 'react-bootstrap'

const backendHost = process.env.BACKEND_HOST;
const backendPort = process.env.BACKEND_PORT;

class App extends Component {
  constructor(props) {
	  super(props);
      this.state = {
        fetchData: [],
		servData: [],
		servName: '',
      }
	  window.app = this;
	}
  
	handleChange = (event) => {
	  let nam = event.target.name;
	  let val = event.target.value
	  this.setState({
		[nam]: val
	  })
	}
	
	componentDidMount() {
	  axios.get("http://"+backendHost+":"+backendPort+"/getplayers")
		  .then((response) => {
			  this.setState({
				  fetchData: response.data
			  })
		  })
	  axios.get("http://"+backendHost+":"+backendPort+"/getserver")
		  .then((response) => {
			  this.setState({
				  servData: response.data[0]
			  })
		  })
	}
	
	render() {
	  let card = this.state.fetchData.map((val, key) => {
		  var x = new Date(val.lastOnline);
		  return (
			  <React.Fragment>
				  <Card style={{ width: '18rem' }} className='m-2'>
					  <Card.Body>
						  <Card.Title>{val.username}</Card.Title>
						  <Card.Text>
							  {val.charname}
							  <br/>
							  Last Online - {x.toDateString()}
						  </Card.Text>
					  </Card.Body>
				  </Card>
			  </React.Fragment>
		  )
	  })

	  return (
		  <div className='App'>
			  <h3>{this.state.servData.name}</h3>
			  {this.state.servData.description}
			  <br/>
			  Its been {this.state.servData.daysSinceStart} days since the Apocalypse.
			  <br/>
			  The ingame date is {this.state.servData.month}/{this.state.servData.dayofmonth}
				
			  <Container>
				  <Row>
					  {card}
				  </Row>
			  </Container>
		  </div>
	  );
	}
}
export default App;

