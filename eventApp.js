/* REACT LOADING */
"use strict";

const replaceColWhiteSpace = col => col.toLowerCase().replace(/[\s]+/g, "-");

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
    { className: "event-details" },
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

const skipColumns = [
  "First Name",
  "Last Name",
  "Gender",
  "Cat",
  "UCIAffiliation"
];

const quickResultColumns = ["Place", "Name", "Club", "Finish Time"];
const priorityColumns = ["Place", "Bib", "Name", "VNBAffiliation", "Club"];

const columnIsNotPrioritized = col => priorityColumns.indexOf(col) < 0;
const columnIsPrioritized = col => priorityColumns.indexOf(col) > 0;

const sortColumnsByPriority = (colA, colB) => {
  if (
    (columnIsNotPrioritized(colA) && columnIsNotPrioritized(colB)) ||
    (columnIsNotPrioritized(colA) && columnIsPrioritized(colB))
  ) {
    return 1;
  }

  if (columnIsPrioritized(colA) && columnIsNotPrioritized(colB)) {
    return -1;
  }

  return priorityColumns.indexOf(colA) - priorityColumns.indexOf(colB);
};

const filterOutSkipColumns = col => !skipColumns.includes(col);

const filterForQuickResults = isDetailed => col =>
  isDetailed || quickResultColumns.includes(col);

const cell = (col, string) =>
  React.createElement(
    "td",
    {
      key: col,
      className: `table-cell ${replaceColWhiteSpace(col)}-cell`
    },
    string
  );

const placeCell = string =>
  React.createElement(
    "td",
    {
      key: `place-cell-${string}`,
      className: "table-cell place-cell"
    },
    React.createElement("span", { className: "place" }, string)
  );

const cellComponent = (col, string) =>
  col === "Place" ? placeCell(string) : cell(col, string);

//TODO: use a stateful "detailed results" to add a column filter
const ParticipantRow = props => {
  return React.createElement("tr", { className: "table-row" }, [
    props.columnHeaders
      .filter(filterForQuickResults(props.isDetailed))
      .filter(filterOutSkipColumns)
      .sort(sortColumnsByPriority)
      .map(col => cellComponent(col, props.rider[col]))
  ]);
};

const HeaderRow = props => {
  return React.createElement(
    "thead",
    null,
    React.createElement("tr", { className: "table-row" }, [
      props.columnHeaders
        .filter(filterForQuickResults(props.isDetailed))
        .filter(filterOutSkipColumns)
        .sort(sortColumnsByPriority)
        .map(col =>
          React.createElement(
            "th",
            { className: `${replaceColWhiteSpace(col)}-header` },
            col
          )
        )
    ])
  );
};

const tableBody = ({ riders, columnHeaders, isDetailed }) =>
  React.createElement(
    "tbody",
    { key: "table-body" },
    riders.map(rider =>
      React.createElement(ParticipantRow, {
        columnHeaders,
        rider,
        isDetailed,
        key: rider["Place"]
      })
    )
  );

const CategoryTable = props => {
  const { isDetailed, riders } = props;
  const tableHeaders = props.cat.split("::");
  const columnHeaders = Object.keys(riders[0] || {});

  return React.createElement("div", { className: "category-table" }, [
    React.createElement(
      "h3",
      { key: "cat-header", className: "category-header" },
      [
        React.createElement(
          "span",
          { key: "category-name" },
          `${tableHeaders[0]} `
        ),
        React.createElement(
          "small",
          { key: "cat-sub-name" },
          `${tableHeaders[1]} `
        )
      ]
    ),
    React.createElement("table", { key: "cat-table" }, [
      React.createElement(HeaderRow, {
        columnHeaders,
        isDetailed,
        key: "header-row"
      }),
      tableBody({ riders, columnHeaders, isDetailed })
    ])
  ]);
};

const EventHeader = ({ name, location, handleClick, isDetailed }) =>
  React.createElement("h1", { className: "event-header" }, [
    React.createElement("span", { key: "name-header" }, `${name} `),
    React.createElement("small", { key: "location-header" }, location),
    React.createElement(
      "button",
      { key: "details-btn", className: "details-btn", onClick: handleClick },
      isDetailed ? "Quick Results" : "Detailed Results"
    )
  ]);

class EventPage extends React.Component {
  constructor(props) {
    super(props);

    this.handleDataLoad = this.handleDataLoad.bind(this);

    window.addEventListener("event::data::loaded", this.handleDataLoad);

    this.state = {
      event: {},
      categories: [],
      participants: {},
      isDetailed: false
    };
  }

  handleDataLoad(e) {
    const data = { ...e.detail.eventData };
    const event = { ...e.detail.eventData.event[0] };

    const categories = Object.keys(data).filter(key => key !== "event");

    const participants = categories.reduce((categorizedRiders, cat) => {
      categorizedRiders[cat] = e.detail.eventData[cat].results;
      return categorizedRiders;
    }, {});

    this.setState({
      event,
      categories,
      participants
    });
  }

  handleDetailedResultsClick = e => {
    this.setState({ isDetailed: !this.state.isDetailed });
  };

  render() {
    const {
      categories,
      event,
      event: { name, location },
      isDetailed,
      participants
    } = this.state;

    return React.createElement("div", { className: "event-page" }, [
      React.createElement(EventHeader, {
        name,
        location,
        isDetailed,
        key: "header-key",
        handleClick: this.handleDetailedResultsClick
      }),
      React.createElement(EventDetails, {
        eventDetails: this.state.event,
        key: "details-key"
      }),
      ...categories.map(cat =>
        React.createElement(CategoryTable, {
          cat,
          isDetailed,
          key: cat,
          riders: participants[cat]
        })
      )
    ]);
  }
}

window.onload = function() {
  ReactDOM.render(
    React.createElement(EventPage, {}),
    document.getElementById("react-root")
  );
};
