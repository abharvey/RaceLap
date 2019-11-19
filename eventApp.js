/* REACT LOADING */
const participantsByCategory = (category, participants = []) =>
  participants.filter(p => category.includes(p.cat));

const detailTitles = {
  organizer: "Race Series",
  date: "Date",
  commissaire: "Commissaire",
  participant: "Participants",
  resultstatus: "Status"
};

const detailsInOrder = [
  "organizer",
  "date",
  "resultstatus",
  "participant",
  "commissaire"
];

const EventDetails = ({ eventDetails }) =>
  React.createElement(
    "ul",
    null,
    detailsInOrder.map(key =>
      React.createElement(
        "li",
        {
          key: `${key}-${eventDetails[key]}`,
          style: { listStyleType: "none" }
        },
        `${detailTitles[key]}: ${eventDetails[key]}`
      )
    )
  );

const ParticipantRow = props => {
  return null;
};

const CategoryTable = props => {
  return null;
};

const EventHeader = ({ name, location }) =>
  React.createElement(
    "h1",
    {
      style: {
        borderBottom: "1px solid #eee",
        display: "flex",
        flexDirection: "row"
      }
    },
    [
      React.createElement("span", { key: "name-header" }, `${name} `),
      React.createElement("small", { key: "location-header" }, location),
      React.createElement(
        "button",
        {
          key: "details-btn",
          className: "details-btn"
        },
        "Detailed Results"
      )
    ]
  );

class EventPage extends React.Component {
  constructor(props) {
    super(props);

    this.handleDataLoad = this.handleDataLoad.bind(this);

    window.addEventListener("event::data::loaded", this.handleDataLoad);

    this.state = {
      event: {},
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
    this.setState({ event: e.detail.eventData.event[0] });
  }

  render() {
    const {
      event,
      event: { name, location }
    } = this.state;

    return React.createElement("div", null, [
      React.createElement(EventHeader, { name, location }),
      React.createElement(EventDetails, {
        eventDetails: this.state.event
      })
    ]);
  }
}

window.onload = function() {
  ReactDOM.render(
    React.createElement(EventPage, {}),
    document.getElementById("react-root")
  );
};
