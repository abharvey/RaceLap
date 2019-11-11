/* REACT LOADING */
const participantsByCategory = (category, participants = []) =>
  participants.filter(p => category.includes(p.cat));

const EventDetails = props => {
  const { eventDetails } = props;
  return React.createElement(
    "div",
    null,
    object
      .Keys(eventDetails)
      .filter(key => key != "name")
      .map(key =>
        React.createElement("span", null, `${key}: ${eventDetails[key]}`)
      )
  );
};

class EventPage extends React.Component {
  constructor(props) {
    super(props);

    this.handleDataLoad = this.handleDataLoad.bind(this);

    window.addEventListener("event::data::loaded", this.handleDataLoad);

    this.state = {
      event: {
        name: "",
        date: "",
        location: "",
        commissaire: "",
        participantCount: "",
        resultstatus: "",
        organizer: "",
        type: ""
      },
      categories: [
        {
          title: "",
          subtitle: ""
        }
      ],
      participants: [{}]
    };
  }

  handleDataLoad(e) {
    console.log(e.detail.eventData.event);
  }

  render() {
    return React.createElement("div", null);
  }
}

window.onload = function() {
  ReactDOM.render(
    React.createElement(EventPage, {}),
    document.getElementById("react-root")
  );
};
