const skipColumns = [
  "First Name",
  "Last Name",
  "Gender",
  "Cat",
  "UCIAffiliation",
]

const detailTitles = {
  organizer: "Race Series",
  date: "Date",
  commissaire: "Commissaire",
  participant: "Participants",
  resultstatus: "Status",
}

const detailsInOrder = [
  "organizer",
  "date",
  "resultstatus",
  "participant",
  "commissaire",
]

const quickResultColumns = ["Place", "Name", "Club", "Finish Time"]
const priorityColumns = ["Place", "Bib", "Name", "VNBAffiliation", "Club"]

const columnIsNotPrioritized = col => priorityColumns.indexOf(col) < 0
const columnIsPrioritized = col => priorityColumns.indexOf(col) >= 0

const filterOutSkipColumns = col => !skipColumns.includes(col)

const filterForQuickResults = isDetailed => col =>
  isDetailed || quickResultColumns.includes(col)

const participantsByCategory = (category, participants = []) =>
  participants.filter(p => category.includes(p.cat))

const sortColumnsByPriority = (colA, colB) => {
  if (columnIsNotPrioritized(colA) && columnIsPrioritized(colB)) {
    return 1
  }

  if (columnIsPrioritized(colA) && columnIsNotPrioritized(colB)) {
    return -1
  }

  return priorityColumns.indexOf(colA) - priorityColumns.indexOf(colB)
}

export {
  columnIsNotPrioritized,
  columnIsPrioritized,
  detailsInOrder,
  detailTitles,
  filterOutSkipColumns,
  filterForQuickResults,
  participantsByCategory,
  priorityColumns,
  quickResultColumns,
  skipColumns,
  sortColumnsByPriority,
}
